<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockTransferService
{
    public function __construct(
        private readonly StockMovementService $stockMovements,
    ) {}

    public function create(array $data, array $items): StockTransfer
    {
        return DB::transaction(function () use ($data, $items): StockTransfer {
            $this->validateTransferPayload($data, $items);

            $transfer = StockTransfer::create([
                'transfer_number' => $this->generateTransferNumber(),
                'from_branch_id' => $data['from_branch_id'],
                'to_branch_id' => $data['to_branch_id'],
                'status' => 'draft',
                'transfer_date' => $data['transfer_date'] ?? now()->toDateString(),
                'notes' => $data['notes'] ?? null,
                'user_id' => $data['created_by'] ?? auth()->id(),
                'created_by' => $data['created_by'] ?? auth()->id(),
            ]);

            $this->syncItems($transfer, $items);

            return $transfer->fresh(['items.productVariant.product', 'fromBranch', 'toBranch']);
        });
    }

    public function updateDraft(StockTransfer $transfer, array $data, array $items): StockTransfer
    {
        if ($transfer->status !== 'draft') {
            throw ValidationException::withMessages(['transfer' => 'Only draft transfers can be edited.']);
        }

        return DB::transaction(function () use ($transfer, $data, $items): StockTransfer {
            $this->validateTransferPayload($data, $items);

            $transfer->update([
                'from_branch_id' => $data['from_branch_id'],
                'to_branch_id' => $data['to_branch_id'],
                'transfer_date' => $data['transfer_date'] ?? $transfer->transfer_date,
                'notes' => $data['notes'] ?? null,
            ]);

            $transfer->items()->delete();
            $this->syncItems($transfer, $items);

            return $transfer->fresh(['items.productVariant.product', 'fromBranch', 'toBranch']);
        });
    }

    public function send(StockTransfer $transfer): StockTransfer
    {
        if ($transfer->status !== 'draft') {
            throw ValidationException::withMessages(['transfer' => 'Only draft transfers can be sent.']);
        }

        return DB::transaction(function () use ($transfer): StockTransfer {
            $transfer->load('items.productVariant.product');

            foreach ($transfer->items as $item) {
                $available = (int) Inventory::query()
                    ->where('branch_id', $transfer->from_branch_id)
                    ->where('product_variant_id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->value('quantity');

                if ($available < (int) $item->quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$item->productVariant?->product?->product_name}.",
                    ]);
                }
            }

            foreach ($transfer->items as $item) {
                $this->stockMovements->recordTransferOut(
                    (int) $transfer->from_branch_id,
                    (int) $item->product_variant_id,
                    (int) $item->quantity,
                    $transfer,
                    "Transfer {$transfer->transfer_number} sent from source branch.",
                    true,
                );

                $item->update(['quantity_sent' => $item->quantity]);
            }

            $transfer->update([
                'status' => 'sent',
                'sent_at' => now(),
                'sent_by' => auth()->id(),
            ]);

            return $transfer->fresh(['items', 'stockMovements']);
        });
    }

    public function receive(StockTransfer $transfer): StockTransfer
    {
        if ($transfer->status !== 'sent') {
            throw ValidationException::withMessages(['transfer' => 'Only sent transfers can be received.']);
        }

        return DB::transaction(function () use ($transfer): StockTransfer {
            $transfer->load('items');

            foreach ($transfer->items as $item) {
                $quantity = (int) ($item->quantity_sent ?: $item->quantity);

                $this->stockMovements->recordTransferIn(
                    (int) $transfer->to_branch_id,
                    (int) $item->product_variant_id,
                    $quantity,
                    $transfer,
                    "Transfer {$transfer->transfer_number} received at destination branch.",
                    true,
                );

                $item->update(['quantity_received' => $quantity]);
            }

            $transfer->update([
                'status' => 'received',
                'received_at' => now(),
                'received_by' => auth()->id(),
            ]);

            return $transfer->fresh(['items', 'stockMovements']);
        });
    }

    public function cancel(StockTransfer $transfer, string $reason): StockTransfer
    {
        if (! in_array($transfer->status, ['draft', 'sent'], true)) {
            throw ValidationException::withMessages(['transfer' => 'Only draft or sent transfers can be cancelled.']);
        }

        return DB::transaction(function () use ($transfer, $reason): StockTransfer {
            $transfer->load('items');

            if ($transfer->status === 'sent') {
                foreach ($transfer->items as $item) {
                    $quantity = (int) ($item->quantity_sent ?: $item->quantity);

                    $this->stockMovements->recordTransferIn(
                        (int) $transfer->from_branch_id,
                        (int) $item->product_variant_id,
                        $quantity,
                        $transfer,
                        "Transfer {$transfer->transfer_number} cancelled after send: {$reason}",
                        true,
                    );
                }
            }

            $transfer->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $reason,
            ]);

            return $transfer->fresh(['items', 'stockMovements']);
        });
    }

    private function syncItems(StockTransfer $transfer, array $items): void
    {
        $variantIds = collect($items)->pluck('product_variant_id')->map(fn ($id) => (int) $id);
        $variants = ProductVariant::query()->whereIn('id', $variantIds)->get()->keyBy('id');

        foreach ($items as $item) {
            $variantId = (int) $item['product_variant_id'];
            $variant = $variants->get($variantId);

            if (! $variant) {
                throw ValidationException::withMessages(['items' => 'Invalid product variant selected.']);
            }

            $transfer->items()->create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variantId,
                'quantity' => (int) $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }
    }

    private function validateTransferPayload(array $data, array $items): void
    {
        validator($data, [
            'from_branch_id' => ['required', 'exists:branches,id', 'different:to_branch_id'],
            'to_branch_id' => ['required', 'exists:branches,id'],
            'transfer_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        if (count($items) < 1) {
            throw ValidationException::withMessages(['items' => 'Add at least one item to the transfer.']);
        }

        $variantIds = [];

        foreach ($items as $index => $item) {
            validator($item, [
                'product_variant_id' => ['required', 'exists:product_variants,id'],
                'quantity' => ['required', 'integer', 'min:1'],
                'notes' => ['nullable', 'string'],
            ], [], [
                'product_variant_id' => 'item '.($index + 1).' product variant',
                'quantity' => 'item '.($index + 1).' quantity',
            ])->validate();

            $variantId = (int) $item['product_variant_id'];

            if (in_array($variantId, $variantIds, true)) {
                throw ValidationException::withMessages(['items' => 'Duplicate product variants are not allowed in the same transfer.']);
            }

            $variantIds[] = $variantId;
        }
    }

    private function generateTransferNumber(): string
    {
        $prefix = 'TRF-'.now()->format('Ymd').'-';

        do {
            $number = $prefix.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (StockTransfer::where('transfer_number', $number)->exists());

        return $number;
    }
}
