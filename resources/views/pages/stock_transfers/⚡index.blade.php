<?php

use App\Models\Branches;
use App\Models\ProductVariant;
use App\Models\StockTransfer;
use App\Services\StockTransferService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Stock Transfers')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public ?int $fromBranchFilter = null;
    public ?int $toBranchFilter = null;
    public string $dateFrom = '';
    public string $dateTo = '';

    public bool $showFormModal = false;
    public bool $showDetailsModal = false;
    public bool $showCancelModal = false;
    public ?int $editingId = null;
    public ?int $viewingId = null;
    public ?int $cancellingId = null;
    public ?int $fromBranchId = null;
    public ?int $toBranchId = null;
    public string $transferDate = '';
    public string $notes = '';
    public string $cancellationReason = '';
    public array $items = [];

    public function mount(): void
    {
        $this->transferDate = now()->format('Y-m-d');
        $this->fromBranchId = Branches::query()->orderBy('id')->value('id');
        $this->toBranchId = Branches::query()->where('id', '!=', $this->fromBranchId)->orderBy('id')->value('id');
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'fromBranchFilter', 'toBranchFilter', 'dateFrom', 'dateTo'], true)) {
            $this->resetPage();
        }
    }

    public function openCreate(): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.create'), 403);
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.edit'), 403);

        $transfer = StockTransfer::query()->with('items')->findOrFail($id);

        if ($transfer->status !== 'draft') {
            $this->addError('transfer', 'Only draft transfers can be edited.');
            return;
        }

        $this->editingId = $transfer->id;
        $this->fromBranchId = $transfer->from_branch_id;
        $this->toBranchId = $transfer->to_branch_id;
        $this->transferDate = $transfer->transfer_date?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->notes = (string) $transfer->notes;
        $this->items = $transfer->items->map(fn ($item): array => [
            'product_variant_id' => $item->product_variant_id,
            'quantity' => (int) $item->quantity,
            'notes' => (string) $item->notes,
        ])->values()->all();
        $this->showFormModal = true;
    }

    public function save(StockTransferService $service): void
    {
        abort_unless(auth()->user()?->can($this->editingId ? 'stock_transfers.edit' : 'stock_transfers.create'), 403);

        $payload = [
            'from_branch_id' => $this->fromBranchId,
            'to_branch_id' => $this->toBranchId,
            'transfer_date' => $this->transferDate,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingId) {
            $service->updateDraft(StockTransfer::findOrFail($this->editingId), $payload, $this->items);
            session()->flash('success', 'Draft transfer updated successfully.');
        } else {
            $service->create($payload, $this->items);
            session()->flash('success', 'Draft transfer created successfully.');
        }

        $this->resetForm();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_variant_id' => null,
            'quantity' => 1,
            'notes' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function viewTransfer(int $id): void
    {
        $this->viewingId = $id;
        $this->showDetailsModal = true;
    }

    public function sendTransfer(int $id, StockTransferService $service): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.send'), 403);
        $service->send(StockTransfer::findOrFail($id));
        session()->flash('success', 'Transfer sent and source stock deducted.');
    }

    public function receiveTransfer(int $id, StockTransferService $service): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.receive'), 403);
        $service->receive(StockTransfer::findOrFail($id));
        session()->flash('success', 'Transfer received and destination stock increased.');
    }

    public function openCancel(int $id): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.cancel'), 403);
        $this->cancellingId = $id;
        $this->cancellationReason = '';
        $this->showCancelModal = true;
    }

    public function cancelTransfer(StockTransferService $service): void
    {
        abort_unless(auth()->user()?->can('stock_transfers.cancel'), 403);

        $this->validate([
            'cancellationReason' => ['required', 'string', 'min:3'],
        ]);

        $service->cancel(StockTransfer::findOrFail($this->cancellingId), $this->cancellationReason);
        $this->showCancelModal = false;
        $this->cancellingId = null;
        $this->cancellationReason = '';
        session()->flash('success', 'Transfer cancelled successfully.');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'fromBranchFilter', 'toBranchFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->showFormModal = false;
        $this->editingId = null;
        $this->fromBranchId = Branches::query()->orderBy('id')->value('id');
        $this->toBranchId = Branches::query()->where('id', '!=', $this->fromBranchId)->orderBy('id')->value('id');
        $this->transferDate = now()->format('Y-m-d');
        $this->notes = '';
        $this->items = [[
            'product_variant_id' => null,
            'quantity' => 1,
            'notes' => '',
        ]];
        $this->resetValidation();
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get();
    }

    public function getProductVariantsProperty()
    {
        return ProductVariant::query()
            ->with(['product', 'color', 'size'])
            ->where('is_active', true)
            ->orderBy('sku')
            ->limit(300)
            ->get();
    }

    public function getTransfersProperty()
    {
        return $this->filteredQuery()
            ->with(['fromBranch', 'toBranch', 'createdBy', 'items'])
            ->latest('transfer_date')
            ->latest('id')
            ->paginate(12);
    }

    public function getStatsProperty(): array
    {
        $base = $this->filteredQuery();

        return [
            'total' => (clone $base)->count(),
            'draft' => (clone $base)->where('status', 'draft')->count(),
            'sent' => (clone $base)->where('status', 'sent')->count(),
            'received' => (clone $base)->where('status', 'received')->count(),
        ];
    }

    public function getViewingTransferProperty(): ?StockTransfer
    {
        if (! $this->viewingId) {
            return null;
        }

        return StockTransfer::query()
            ->with([
                'fromBranch',
                'toBranch',
                'createdBy',
                'sentBy',
                'receivedBy',
                'cancelledBy',
                'items.productVariant.product',
                'items.productVariant.color',
                'items.productVariant.size',
                'stockMovements.createdBy',
                'stockMovements.branch',
            ])
            ->find($this->viewingId);
    }

    public function statusBadge(string $status): string
    {
        return match ($status) {
            'draft' => 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300',
            'sent' => 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300',
            'received' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300',
            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    private function filteredQuery()
    {
        return StockTransfer::query()
            ->when($this->search, function ($query): void {
                $query->where(function ($inner): void {
                    $inner->where('transfer_number', 'like', "%{$this->search}%")
                        ->orWhereHas('items.productVariant', fn ($variant) => $variant->where('sku', 'like', "%{$this->search}%"))
                        ->orWhereHas('items.productVariant.product', fn ($product) => $product->where('product_name', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->fromBranchFilter, fn ($query) => $query->where('from_branch_id', $this->fromBranchFilter))
            ->when($this->toBranchFilter, fn ($query) => $query->where('to_branch_id', $this->toBranchFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('transfer_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('transfer_date', '<=', $this->dateTo));
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        @error('transfer') <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div> @enderror
        @error('items') <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div> @enderror

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Inventory / مخزون</p>
                <h1 class="text-2xl font-bold tracking-tight">Stock Transfers</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Move inventory between branches with send, receive, cancel, and immutable stock movement audit tracking.</p>
            </div>
            @can('stock_transfers.create')
                <button type="button" wire:click="openCreate" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Create Transfer</button>
            @endcan
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['Total Transfers', $this->stats['total']], ['Draft', $this->stats['draft']], ['Sent', $this->stats['sent']], ['Received', $this->stats['received']]] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ number_format($value) }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-7">
                <input wire:model.live.debounce.350ms="search" placeholder="Search transfer, product, or SKU" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="status" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All statuses</option>
                    @foreach (['draft', 'sent', 'received', 'cancelled'] as $state)
                        <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="fromBranchFilter" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">From branch</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="toBranchFilter" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">To branch</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Reset</button>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading class="w-full border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">Loading stock transfers...</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Transfer No', 'Date', 'From', 'To', 'Items', 'Qty', 'Status', 'Created By', 'Actions'] as $heading)
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->transfers as $transfer)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-zinc-800/60">
                                <td class="whitespace-nowrap px-4 py-3 font-semibold">{{ $transfer->transfer_number }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $transfer->transfer_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $transfer->fromBranch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $transfer->toBranch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $transfer->items->count() }}</td>
                                <td class="px-4 py-3">{{ number_format($transfer->items->sum('quantity')) }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->statusBadge($transfer->status) }}">{{ ucfirst($transfer->status) }}</span></td>
                                <td class="px-4 py-3">{{ $transfer->createdBy?->name ?? $transfer->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" wire:click="viewTransfer({{ $transfer->id }})" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</button>
                                        @if ($transfer->status === 'draft')
                                            @can('stock_transfers.edit')
                                                <button type="button" wire:click="openEdit({{ $transfer->id }})" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Edit</button>
                                            @endcan
                                            @can('stock_transfers.send')
                                                <button type="button" wire:click="sendTransfer({{ $transfer->id }})" wire:confirm="Send this transfer and deduct stock from source branch?" class="rounded-md bg-blue-600 px-2 py-1 text-xs font-semibold text-white">Send</button>
                                            @endcan
                                        @endif
                                        @if ($transfer->status === 'sent')
                                            @can('stock_transfers.receive')
                                                <button type="button" wire:click="receiveTransfer({{ $transfer->id }})" wire:confirm="Receive this transfer and add stock to destination branch?" class="rounded-md bg-emerald-600 px-2 py-1 text-xs font-semibold text-white">Receive</button>
                                            @endcan
                                        @endif
                                        @if (in_array($transfer->status, ['draft', 'sent'], true))
                                            @can('stock_transfers.cancel')
                                                <button type="button" wire:click="openCancel({{ $transfer->id }})" class="rounded-md bg-red-600 px-2 py-1 text-xs font-semibold text-white">Cancel</button>
                                            @endcan
                                        @endif
                                        @can('stock_transfers.print')
                                            <a href="{{ route('stock-transfers.print', $transfer) }}" target="_blank" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Print</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-slate-500 dark:text-zinc-400">No stock transfers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->transfers->links() }}</div>
        </div>
    </div>

    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="max-h-[92vh] w-full max-w-5xl overflow-y-auto rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">{{ $editingId ? 'Edit Draft Transfer' : 'Create Stock Transfer' }}</h2>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Draft transfers do not affect inventory until sent.</p>
                    </div>
                    <button type="button" wire:click="resetForm" class="rounded-lg border px-3 py-1 text-sm dark:border-zinc-700">Close</button>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <select wire:model="fromBranchId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">From branch</option>
                        @foreach ($this->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                    <select wire:model="toBranchId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">To branch</option>
                        @foreach ($this->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                    <input type="date" wire:model="transferDate" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <textarea wire:model="notes" rows="2" placeholder="Transfer notes" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950 md:col-span-3"></textarea>
                </div>

                <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200 dark:border-zinc-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                            <tr><th class="px-4 py-3 text-left">Product Variant</th><th class="px-4 py-3 text-left">Quantity</th><th class="px-4 py-3 text-left">Notes</th><th></th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @foreach ($items as $index => $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <select wire:model="items.{{ $index }}.product_variant_id" class="w-96 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                            <option value="">Select variant</option>
                                            @foreach ($this->productVariants as $variant)
                                                <option value="{{ $variant->id }}">{{ $variant->product?->product_name }} · {{ $variant->sku }} · {{ $variant->color?->color_name }} {{ $variant->size?->size_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3"><input type="number" min="1" wire:model="items.{{ $index }}.quantity" class="w-28 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                    <td class="px-4 py-3"><input type="text" wire:model="items.{{ $index }}.notes" class="w-64 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                    <td class="px-4 py-3 text-right"><button type="button" wire:click="removeItem({{ $index }})" class="rounded-md px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">Remove</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" wire:click="addItem" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700">Add Item</button>
                </div>
                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="resetForm" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Draft</button>
                </div>
            </div>
        </div>
    @endif

    @if ($showDetailsModal && $this->viewingTransfer)
        @php($transfer = $this->viewingTransfer)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">{{ $transfer->transfer_number }}</h2>
                        <p class="text-sm text-slate-500">{{ $transfer->fromBranch?->branch_name }} → {{ $transfer->toBranch?->branch_name }}</p>
                    </div>
                    <button type="button" wire:click="$set('showDetailsModal', false)" class="rounded-lg border px-3 py-1 text-sm dark:border-zinc-700">Close</button>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-zinc-950"><p class="text-xs text-slate-500">Status</p><span class="mt-1 inline-block rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->statusBadge($transfer->status) }}">{{ ucfirst($transfer->status) }}</span></div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-zinc-950"><p class="text-xs text-slate-500">Created By</p><p class="font-semibold">{{ $transfer->createdBy?->name ?? $transfer->user?->name ?? '-' }}</p></div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-zinc-950"><p class="text-xs text-slate-500">Sent</p><p class="font-semibold">{{ $transfer->sent_at?->format('Y-m-d H:i') ?? '-' }}</p></div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-zinc-950"><p class="text-xs text-slate-500">Received</p><p class="font-semibold">{{ $transfer->received_at?->format('Y-m-d H:i') ?? '-' }}</p></div>
                </div>
                <h3 class="mt-6 font-bold">Items</h3>
                <div class="mt-3 overflow-x-auto rounded-xl border border-slate-200 dark:border-zinc-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900"><tr><th class="px-4 py-3 text-left">Product</th><th>SKU</th><th>Color</th><th>Size</th><th>Qty</th><th>Sent</th><th>Received</th><th>Notes</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @foreach ($transfer->items as $item)
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $item->productVariant?->product?->product_name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->productVariant?->sku ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->productVariant?->color?->color_name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->productVariant?->size?->size_name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity_sent ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity_received ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h3 class="mt-6 font-bold">Related Stock Movements</h3>
                <div class="mt-3 space-y-2">
                    @forelse ($transfer->stockMovements->sortBy('movement_date') as $movement)
                        <div class="flex flex-wrap justify-between gap-2 rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-950">
                            <span>{{ $movement->movement_date?->format('Y-m-d H:i') }} · {{ str_replace('_', ' ', $movement->movement_type) }} · {{ strtoupper($movement->direction) }} · {{ $movement->branch?->branch_name }}</span>
                            <span class="font-semibold">{{ $movement->quantity_before }} → {{ $movement->quantity_after }} ({{ $movement->quantity }})</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No stock movements yet. Draft transfers do not affect inventory.</p>
                    @endforelse
                </div>
                <h3 class="mt-6 font-bold">Activity Timeline</h3>
                <div class="mt-3 grid gap-2 md:grid-cols-4">
                    <div class="rounded-lg border border-slate-200 p-3 text-sm dark:border-zinc-800">Created<br><strong>{{ $transfer->created_at?->format('Y-m-d H:i') }}</strong></div>
                    <div class="rounded-lg border border-slate-200 p-3 text-sm dark:border-zinc-800">Sent<br><strong>{{ $transfer->sentBy?->name ?? '-' }}</strong></div>
                    <div class="rounded-lg border border-slate-200 p-3 text-sm dark:border-zinc-800">Received<br><strong>{{ $transfer->receivedBy?->name ?? '-' }}</strong></div>
                    <div class="rounded-lg border border-slate-200 p-3 text-sm dark:border-zinc-800">Cancelled<br><strong>{{ $transfer->cancelledBy?->name ?? '-' }}</strong></div>
                </div>
                @if ($transfer->cancellation_reason)
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">Cancellation reason: {{ $transfer->cancellation_reason }}</div>
                @endif
            </div>
        </div>
    @endif

    @if ($showCancelModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/70 p-4">
            <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <h2 class="text-lg font-bold">Cancel Transfer</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">Cancelling a sent transfer will restore deducted stock to the source branch.</p>
                <textarea wire:model="cancellationReason" rows="3" class="mt-4 w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950" placeholder="Cancellation reason"></textarea>
                @error('cancellationReason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('showCancelModal', false)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Back</button>
                    <button type="button" wire:click="cancelTransfer" wire:loading.attr="disabled" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Cancel Transfer</button>
                </div>
            </div>
        </div>
    @endif
</div>
