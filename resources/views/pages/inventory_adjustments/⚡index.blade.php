<?php

use App\Models\Branches;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\ProductVariant;
use App\Services\InventoryAdjustmentService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Inventory Adjustments')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $branchId = null;
    public string $adjustmentType = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public bool $showCreateModal = false;
    public bool $showConfirmModal = false;
    public ?int $formBranchId = null;
    public ?int $formProductVariantId = null;
    public ?int $newQuantity = null;
    public string $formAdjustmentType = 'stock_count';
    public string $reason = '';
    public string $notes = '';

    public function mount(): void
    {
        $this->formBranchId = Branches::query()->orderBy('id')->value('id');
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'branchId', 'adjustmentType', 'dateFrom', 'dateTo'], true)) {
            $this->resetPage();
        }
    }

    public function openCreateModal(): void
    {
        abort_unless(auth()->user()?->can('inventory_adjustments.create'), 403);
        $this->resetValidation();
        $this->showCreateModal = true;
        $this->showConfirmModal = false;
    }

    public function prepareCreate(): void
    {
        abort_unless(auth()->user()?->can('inventory_adjustments.create'), 403);

        $this->validate($this->formRules());

        if ($this->difference === 0) {
            $this->addError('newQuantity', 'New quantity must be different from current quantity.');
            return;
        }

        $this->showConfirmModal = true;
    }

    public function createAdjustment(InventoryAdjustmentService $service): void
    {
        abort_unless(auth()->user()?->can('inventory_adjustments.create'), 403);

        $this->validate($this->formRules());

        if ($this->difference === 0) {
            $this->addError('newQuantity', 'New quantity must be different from current quantity.');
            $this->showConfirmModal = false;
            return;
        }

        $service->create([
            'branch_id' => $this->formBranchId,
            'product_variant_id' => $this->formProductVariantId,
            'quantity_after' => $this->newQuantity,
            'adjustment_type' => $this->formAdjustmentType,
            'reason' => $this->reason,
            'notes' => $this->notes ?: null,
        ]);

        $this->resetCreateForm();
        session()->flash('success', 'Inventory adjustment created successfully.');
    }

    public function resetCreateForm(): void
    {
        $this->showCreateModal = false;
        $this->showConfirmModal = false;
        $this->formProductVariantId = null;
        $this->newQuantity = null;
        $this->formAdjustmentType = 'stock_count';
        $this->reason = '';
        $this->notes = '';
        $this->resetValidation();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'branchId', 'adjustmentType', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function exportCsv()
    {
        abort_unless(auth()->user()?->can('inventory_adjustments.export'), 403);

        $fileName = 'inventory-adjustments-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Adjustment Number',
                'Date',
                'Branch',
                'Product',
                'SKU',
                'Color',
                'Size',
                'Quantity Before',
                'Quantity After',
                'Difference',
                'Type',
                'Reason',
                'Notes',
                'Created By',
            ]);

            $this->filteredQuery()
                ->with(['branch', 'productVariant.product', 'productVariant.color', 'productVariant.size', 'createdBy'])
                ->latest('adjustment_date')
                ->chunk(500, function ($adjustments) use ($handle): void {
                    foreach ($adjustments as $adjustment) {
                        fputcsv($handle, [
                            $adjustment->adjustment_number,
                            $adjustment->adjustment_date?->format('Y-m-d H:i:s'),
                            $adjustment->branch?->branch_name,
                            $adjustment->productVariant?->product?->product_name,
                            $adjustment->productVariant?->sku,
                            $adjustment->productVariant?->color?->color_name,
                            $adjustment->productVariant?->size?->size_name,
                            $adjustment->quantity_before,
                            $adjustment->quantity_after,
                            $adjustment->adjustment_quantity,
                            $adjustment->adjustment_type,
                            $adjustment->reason,
                            $adjustment->notes,
                            $adjustment->createdBy?->name,
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
            ->where('is_active', true)
            ->orderBy('sku')
            ->limit(300)
            ->get();
    }

    public function getCurrentQuantityProperty(): int
    {
        if (! $this->formBranchId || ! $this->formProductVariantId) {
            return 0;
        }

        return (int) Inventory::query()
            ->where('branch_id', $this->formBranchId)
            ->where('product_variant_id', $this->formProductVariantId)
            ->value('quantity');
    }

    public function getDifferenceProperty(): int
    {
        if ($this->newQuantity === null) {
            return 0;
        }

        return (int) $this->newQuantity - $this->currentQuantity;
    }

    public function getStatsProperty(): array
    {
        $base = $this->filteredQuery();

        return [
            'total' => (clone $base)->count(),
            'increased' => (clone $base)->where('adjustment_quantity', '>', 0)->sum('adjustment_quantity'),
            'decreased' => abs((int) (clone $base)->where('adjustment_quantity', '<', 0)->sum('adjustment_quantity')),
            'damaged' => abs((int) (clone $base)->where('adjustment_type', 'damaged')->sum('adjustment_quantity')),
        ];
    }

    public function getAdjustmentsProperty()
    {
        return $this->filteredQuery()
            ->with(['branch', 'productVariant.product', 'productVariant.color', 'productVariant.size', 'createdBy'])
            ->latest('adjustment_date')
            ->paginate(15);
    }

    public function typeLabel(string $type): string
    {
        return str($type)->replace('_', ' ')->title()->toString();
    }

    private function filteredQuery()
    {
        return InventoryAdjustment::query()
            ->when($this->search, function ($query): void {
                $query->where(function ($inner): void {
                    $inner->where('adjustment_number', 'like', "%{$this->search}%")
                        ->orWhereHas('productVariant', fn ($variant) => $variant->where('sku', 'like', "%{$this->search}%"))
                        ->orWhereHas('productVariant.product', fn ($product) => $product->where('product_name', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId))
            ->when($this->adjustmentType, fn ($query) => $query->where('adjustment_type', $this->adjustmentType))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('adjustment_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('adjustment_date', '<=', $this->dateTo));
    }

    private function formRules(): array
    {
        return [
            'formBranchId' => ['required', 'exists:branches,id'],
            'formProductVariantId' => ['required', 'exists:product_variants,id'],
            'newQuantity' => ['required', 'integer', 'min:0'],
            'formAdjustmentType' => ['required', 'in:'.implode(',', InventoryAdjustment::TYPES)],
            'reason' => ['required', 'string', 'min:3'],
            'notes' => ['nullable', 'string'],
        ];
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Inventory / مخزون</p>
                <h1 class="text-2xl font-bold tracking-tight">Inventory Adjustments</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Audit correction documents for stock counts, damaged items, shortages, overstock, and data entry mistakes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('inventory_adjustments.export')
                    <button type="button" wire:click="exportCsv" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-white dark:border-zinc-700 dark:hover:bg-zinc-900">Export CSV</button>
                @endcan
                @can('inventory_adjustments.create')
                    <button type="button" wire:click="openCreateModal" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Create Adjustment</button>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Total Adjustments', number_format($this->stats['total'])],
                ['Total Quantity Increased', number_format($this->stats['increased'])],
                ['Total Quantity Decreased', number_format($this->stats['decreased'])],
                ['Damaged Items Count', number_format($this->stats['damaged'])],
            ] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-6">
                <input wire:model.live.debounce.350ms="search" placeholder="Search product, SKU, or adjustment no" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="branchId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All branches</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="adjustmentType" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All types</option>
                    @foreach (\App\Models\InventoryAdjustment::TYPES as $type)
                        <option value="{{ $type }}">{{ $this->typeLabel($type) }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Reset</button>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading class="w-full border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">
                Loading inventory adjustments...
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Adjustment Number', 'Date', 'Branch', 'Product', 'SKU', 'Color', 'Size', 'Quantity Before', 'Quantity After', 'Difference', 'Type', 'Reason', 'Created By'] as $heading)
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->adjustments as $adjustment)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-zinc-800/60">
                                <td class="whitespace-nowrap px-4 py-3 font-semibold">{{ $adjustment->adjustment_number }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $adjustment->adjustment_date?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3">{{ $adjustment->branch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $adjustment->productVariant?->product?->product_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $adjustment->productVariant?->sku ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $adjustment->productVariant?->color?->color_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $adjustment->productVariant?->size?->size_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ number_format($adjustment->quantity_before) }}</td>
                                <td class="px-4 py-3">{{ number_format($adjustment->quantity_after) }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $adjustment->adjustment_quantity > 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' }}">
                                        {{ $adjustment->adjustment_quantity > 0 ? '+' : '' }}{{ number_format($adjustment->adjustment_quantity) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">{{ $this->typeLabel($adjustment->adjustment_type) }}</span>
                                </td>
                                <td class="max-w-sm px-4 py-3 text-slate-600 dark:text-zinc-300">{{ $adjustment->reason }}</td>
                                <td class="px-4 py-3">{{ $adjustment->createdBy?->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <p class="font-semibold text-slate-700 dark:text-zinc-200">No inventory adjustments found</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Create a new adjustment to correct stock counts. Historical adjustment records are read-only.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->adjustments->links() }}</div>
        </div>
    </div>

    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="w-full max-w-3xl rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Create Inventory Adjustment</h2>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">This creates a read-only correction document and stock movement audit record.</p>
                    </div>
                    <button type="button" wire:click="resetCreateForm" class="rounded-lg border px-3 py-1 text-sm dark:border-zinc-700">Close</button>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <label class="text-sm font-medium">Branch
                        <select wire:model.live="formBranchId" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Select branch</option>
                            @foreach ($this->branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                        @error('formBranchId') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="text-sm font-medium">Product Variant
                        <select wire:model.live="formProductVariantId" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Select product variant</option>
                            @foreach ($this->productVariants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->product?->product_name }} · {{ $variant->sku }} · {{ $variant->color?->color_name }} {{ $variant->size?->size_name }}</option>
                            @endforeach
                        </select>
                        @error('formProductVariantId') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="text-sm font-medium">Current Quantity
                        <input type="number" readonly value="{{ $this->currentQuantity }}" class="mt-1 w-full rounded-lg border-slate-200 bg-slate-50 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    </label>

                    <label class="text-sm font-medium">New Quantity
                        <input type="number" min="0" wire:model.live="newQuantity" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @error('newQuantity') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-zinc-800 dark:bg-zinc-950">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Difference</p>
                        <p class="mt-1 text-xl font-bold {{ $this->difference > 0 ? 'text-emerald-600' : ($this->difference < 0 ? 'text-red-600' : 'text-slate-500') }}">
                            {{ $this->difference > 0 ? '+' : '' }}{{ number_format($this->difference) }}
                        </p>
                        <p class="text-xs text-slate-500">{{ $this->difference > 0 ? 'Stock movement direction: IN' : ($this->difference < 0 ? 'Stock movement direction: OUT' : 'Enter a different quantity') }}</p>
                    </div>

                    <label class="text-sm font-medium">Adjustment Type
                        <select wire:model="formAdjustmentType" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                            @foreach (\App\Models\InventoryAdjustment::TYPES as $type)
                                <option value="{{ $type }}">{{ $this->typeLabel($type) }}</option>
                            @endforeach
                        </select>
                        @error('formAdjustmentType') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="text-sm font-medium md:col-span-2">Reason
                        <textarea wire:model="reason" rows="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950" placeholder="Reason is required"></textarea>
                        @error('reason') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <label class="text-sm font-medium md:col-span-2">Notes
                        <textarea wire:model="notes" rows="2" class="mt-1 w-full rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950" placeholder="Optional notes"></textarea>
                    </label>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="resetCreateForm" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="prepareCreate" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Review & Confirm</button>
                </div>
            </div>
        </div>
    @endif

    @if ($showConfirmModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/70 p-4">
            <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <h2 class="text-lg font-bold">Confirm Inventory Adjustment</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">
                    This will set stock from <strong>{{ number_format($this->currentQuantity) }}</strong> to <strong>{{ number_format((int) $newQuantity) }}</strong>
                    and create an immutable stock movement of <strong>{{ abs($this->difference) }}</strong> {{ $this->difference > 0 ? 'IN' : 'OUT' }}.
                </p>
                <p class="mt-2 text-sm font-semibold text-amber-700 dark:text-amber-300">This record cannot be edited or deleted after creation.</p>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('showConfirmModal', false)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Back</button>
                    <button type="button" wire:click="createAdjustment" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create Adjustment</button>
                </div>
            </div>
        </div>
    @endif
</div>
