<?php

use App\Models\Branches;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Stock Movements')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $branchId = null;
    public string $movementType = '';
    public string $direction = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public ?int $productVariantId = null;

    public function updated($property): void
    {
        if (in_array($property, ['search', 'branchId', 'movementType', 'direction', 'dateFrom', 'dateTo', 'productVariantId'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'branchId', 'movementType', 'direction', 'dateFrom', 'dateTo', 'productVariantId']);
        $this->resetPage();
    }

    public function exportCsv()
    {
        abort_unless(auth()->user()?->can('stock_movements.export'), 403);

        $fileName = 'stock-movements-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Date',
                'Branch',
                'Product',
                'SKU',
                'Color',
                'Size',
                'Movement Type',
                'Direction',
                'Quantity',
                'Quantity Before',
                'Quantity After',
                'Reference',
                'Created By',
                'Notes',
            ]);

            $this->filteredQuery()
                ->with(['branch', 'product', 'productVariant.product', 'productVariant.color', 'productVariant.size', 'createdBy'])
                ->latest('movement_date')
                ->chunk(500, function ($movements) use ($handle): void {
                    foreach ($movements as $movement) {
                        fputcsv($handle, [
                            $movement->movement_date?->format('Y-m-d H:i:s'),
                            $movement->branch?->branch_name,
                            $movement->product?->product_name ?? $movement->productVariant?->product?->product_name,
                            $movement->productVariant?->sku,
                            $movement->productVariant?->color?->color_name,
                            $movement->productVariant?->size?->size_name,
                            $movement->movement_type,
                            $movement->direction,
                            $movement->quantity,
                            $movement->quantity_before,
                            $movement->quantity_after,
                            $this->referenceLabel($movement),
                            $movement->createdBy?->name,
                            $movement->notes,
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get();
    }

    public function getProductVariantsProperty()
    {
        return ProductVariant::query()
            ->with(['product', 'color', 'size'])
            ->orderBy('sku')
            ->limit(250)
            ->get();
    }

    public function getStatsProperty(): array
    {
        $base = $this->filteredQuery();

        return [
            'total' => (clone $base)->count(),
            'in' => (clone $base)->where('direction', 'in')->sum('quantity'),
            'out' => (clone $base)->where('direction', 'out')->sum('quantity'),
            'variants' => (clone $base)->distinct('product_variant_id')->count('product_variant_id'),
        ];
    }

    public function getMovementsProperty()
    {
        return $this->filteredQuery()
            ->with(['branch', 'product', 'productVariant.product', 'productVariant.color', 'productVariant.size', 'createdBy'])
            ->latest('movement_date')
            ->paginate(15);
    }

    private function filteredQuery()
    {
        return StockMovement::query()
            ->when($this->search, function ($query): void {
                $query->where(function ($inner): void {
                    $inner->whereHas('product', fn ($product) => $product->where('product_name', 'like', "%{$this->search}%"))
                        ->orWhereHas('productVariant', fn ($variant) => $variant->where('sku', 'like', "%{$this->search}%"))
                        ->orWhereHas('productVariant.product', fn ($product) => $product->where('product_name', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId))
            ->when($this->movementType, fn ($query) => $query->where('movement_type', $this->movementType))
            ->when($this->direction, fn ($query) => $query->where('direction', $this->direction))
            ->when($this->productVariantId, fn ($query) => $query->where('product_variant_id', $this->productVariantId))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('movement_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('movement_date', '<=', $this->dateTo));
    }

    public function referenceLabel(StockMovement $movement): string
    {
        if (! $movement->reference_type || ! $movement->reference_id) {
            return '-';
        }

        return class_basename($movement->reference_type).' #'.$movement->reference_id;
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Inventory / مخزون</p>
                <h1 class="text-2xl font-bold tracking-tight">Stock Movements</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Immutable audit trail for every system-created inventory change.</p>
            </div>
            @can('stock_movements.export')
                <button type="button" wire:click="exportCsv" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                    Export CSV
                </button>
            @endcan
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Total Movements', number_format($this->stats['total'])],
                ['Stock In', number_format($this->stats['in'])],
                ['Stock Out', number_format($this->stats['out'])],
                ['Variants Affected', number_format($this->stats['variants'])],
            ] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-7">
                <input wire:model.live.debounce.350ms="search" placeholder="Search product or SKU" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="branchId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All branches</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="movementType" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All types</option>
                    @foreach (\App\Models\StockMovement::TYPES as $type)
                        <option value="{{ $type }}">{{ str_replace('_', ' ', ucfirst($type)) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="direction" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All directions</option>
                    <option value="in">In</option>
                    <option value="out">Out</option>
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Reset</button>
                <select wire:model.live="productVariantId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950 md:col-span-2 xl:col-span-3">
                    <option value="">All variants</option>
                    @foreach ($this->productVariants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->product?->product_name }} · {{ $variant->sku }} · {{ $variant->color?->color_name }} {{ $variant->size?->size_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading class="w-full border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">
                Loading stock movements...
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Date', 'Branch', 'Product', 'SKU', 'Color', 'Size', 'Movement Type', 'Direction', 'Quantity', 'Before', 'After', 'Reference', 'Created By', 'Notes'] as $heading)
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->movements as $movement)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-zinc-800/60">
                                <td class="whitespace-nowrap px-4 py-3">{{ $movement->movement_date?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3">{{ $movement->branch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $movement->product?->product_name ?? $movement->productVariant?->product?->product_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->productVariant?->sku ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->productVariant?->color?->color_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->productVariant?->size?->size_name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">{{ str_replace('_', ' ', ucfirst($movement->movement_type)) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $movement->direction === 'in' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' }}">{{ strtoupper($movement->direction) }}</span>
                                </td>
                                <td class="px-4 py-3 font-semibold">{{ number_format($movement->quantity) }}</td>
                                <td class="px-4 py-3">{{ $movement->quantity_before ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->quantity_after ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $this->referenceLabel($movement) }}</td>
                                <td class="px-4 py-3">{{ $movement->createdBy?->name ?? 'System' }}</td>
                                <td class="max-w-xs px-4 py-3 text-slate-500 dark:text-zinc-400">{{ $movement->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <p class="font-semibold text-slate-700 dark:text-zinc-200">No stock movements found</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Movements are created automatically by purchases, sales, returns, transfers, and future adjustments.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->movements->links() }}</div>
        </div>
    </div>
</div>
