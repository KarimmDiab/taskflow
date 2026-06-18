<?php

use App\Models\Branches;
use App\Models\ProductVariant;
use App\Services\BarcodeService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Barcode Labels')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $branchId = null;
    public string $barcodeStatus = '';
    public array $selected = [];
    public array $labelQuantities = [];
    public ?int $regenerateId = null;

    public function updated($property): void
    {
        if (in_array($property, ['search', 'branchId', 'barcodeStatus'], true)) {
            $this->resetPage();
        }
    }

    public function generateMissing(BarcodeService $barcodes): void
    {
        abort_unless(auth()->user()?->can('barcodes.generate'), 403);

        $count = $barcodes->generateMissing();
        session()->flash('success', "{$count} missing barcode(s) generated.");
    }

    public function confirmRegenerate(int $variantId): void
    {
        abort_unless(auth()->user()?->can('barcodes.regenerate'), 403);
        $this->regenerateId = $variantId;
    }

    public function regenerate(BarcodeService $barcodes): void
    {
        abort_unless(auth()->user()?->can('barcodes.regenerate'), 403);

        $variant = ProductVariant::findOrFail($this->regenerateId);
        $barcodes->regenerate($variant);
        $this->regenerateId = null;
        session()->flash('success', 'Barcode regenerated successfully.');
    }

    public function printSingle(int $variantId)
    {
        abort_unless(auth()->user()?->can('barcodes.print'), 403);

        $variant = ProductVariant::findOrFail($variantId);

        if (! $variant->barcode) {
            $this->addError('barcode', 'Generate a barcode before printing this label.');
            return null;
        }

        return redirect()->route('barcodes.print', [
            'labels' => $this->encodeLabels([['id' => $variantId, 'quantity' => max(1, (int) ($this->labelQuantities[$variantId] ?? 1))]]),
        ]);
    }

    public function printSelected()
    {
        abort_unless(auth()->user()?->can('barcodes.print'), 403);

        $labels = collect($this->selected)
            ->filter()
            ->keys()
            ->map(fn ($id): array => ['id' => (int) $id, 'quantity' => max(1, (int) ($this->labelQuantities[$id] ?? 1))])
            ->values()
            ->all();

        if (empty($labels)) {
            $this->addError('selected', 'Select at least one variant to print.');
            return null;
        }

        $missing = ProductVariant::query()
            ->whereIn('id', collect($labels)->pluck('id'))
            ->where(fn ($query) => $query->whereNull('barcode')->orWhere('barcode', ''))
            ->exists();

        if ($missing) {
            $this->addError('selected', 'All selected variants must have barcodes before printing.');
            return null;
        }

        return redirect()->route('barcodes.print', ['labels' => $this->encodeLabels($labels)]);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'branchId', 'barcodeStatus']);
        $this->resetPage();
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get();
    }

    public function getVariantsProperty()
    {
        return $this->filteredQuery()
            ->with(['product', 'color', 'size', 'inventories.branch'])
            ->latest()
            ->paginate(12);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => ProductVariant::query()->count(),
            'with_barcode' => ProductVariant::query()->whereNotNull('barcode')->where('barcode', '!=', '')->count(),
            'missing' => ProductVariant::query()->where(fn ($query) => $query->whereNull('barcode')->orWhere('barcode', ''))->count(),
            'selected' => collect($this->selected)->filter()->count(),
        ];
    }

    public function barcodeSvg(?string $barcode): string
    {
        if (! $barcode) {
            return '';
        }

        return app(BarcodeService::class)->svg($barcode, 42, 1);
    }

    private function filteredQuery()
    {
        return ProductVariant::query()
            ->when($this->search, function ($query): void {
                $query->where(function ($inner): void {
                    $inner->where('sku', 'like', "%{$this->search}%")
                        ->orWhere('barcode', 'like', "%{$this->search}%")
                        ->orWhereHas('product', fn ($product) => $product->where('product_name', 'like', "%{$this->search}%")->orWhere('product_code', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->branchId, fn ($query) => $query->whereHas('inventories', fn ($inventory) => $inventory->where('branch_id', $this->branchId)))
            ->when($this->barcodeStatus === 'with', fn ($query) => $query->whereNotNull('barcode')->where('barcode', '!=', ''))
            ->when($this->barcodeStatus === 'missing', fn ($query) => $query->where(fn ($inner) => $inner->whereNull('barcode')->orWhere('barcode', '')));
    }

    private function encodeLabels(array $labels): string
    {
        return rtrim(strtr(base64_encode(json_encode($labels)), '+/', '-_'), '=');
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        @error('selected') <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div> @enderror
        @error('barcode') <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div> @enderror

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Products & Inventory</p>
                <h1 class="text-2xl font-bold tracking-tight">Barcode Labels</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Generate, manage, and print product variant barcode labels.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('barcodes.generate')
                    <button type="button" wire:click="generateMissing" wire:loading.attr="disabled" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-white dark:border-zinc-700 dark:hover:bg-zinc-900">Generate Missing</button>
                @endcan
                @can('barcodes.print')
                    <button type="button" wire:click="printSelected" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Print Selected</button>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['Total Variants', $this->stats['total']], ['With Barcode', $this->stats['with_barcode']], ['Missing Barcodes', $this->stats['missing']], ['Selected Labels', $this->stats['selected']]] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ number_format($value) }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-4">
                <input wire:model.live.debounce.350ms="search" placeholder="Search product, SKU, or barcode" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="branchId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All branches</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="barcodeStatus" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All barcode states</option>
                    <option value="with">With barcode</option>
                    <option value="missing">Missing barcode</option>
                </select>
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Reset</button>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading class="w-full border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">Loading barcode labels...</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Select', 'Product', 'SKU', 'Barcode', 'Barcode Image', 'Color', 'Size', 'Price', 'Labels', 'Actions'] as $heading)
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->variants as $variant)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3"><input type="checkbox" wire:model.live="selected.{{ $variant->id }}" class="rounded border-slate-300"></td>
                                <td class="px-4 py-3 font-semibold">{{ $variant->product?->product_name ?? '-' }}</td>
                                <td class="px-4 py-3 font-mono">{{ $variant->sku ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($variant->barcode)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">{{ $variant->barcode }}</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950 dark:text-amber-300">Missing</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="h-14 w-44 rounded border border-slate-200 bg-white p-1">
                                        {!! $this->barcodeSvg($variant->barcode) !!}
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $variant->color?->color_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $variant->size?->size_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $variant->variant_price, 2) }}</td>
                                <td class="px-4 py-3"><input type="number" min="1" wire:model="labelQuantities.{{ $variant->id }}" placeholder="1" class="w-20 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @can('barcodes.print')
                                            <button type="button" wire:click="printSingle({{ $variant->id }})" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Print</button>
                                        @endcan
                                        @can('barcodes.regenerate')
                                            <button type="button" wire:click="confirmRegenerate({{ $variant->id }})" class="rounded-md bg-amber-600 px-2 py-1 text-xs font-semibold text-white">Regenerate</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-slate-500 dark:text-zinc-400">No variants match the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->variants->links() }}</div>
        </div>
    </div>

    @if ($regenerateId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <h2 class="text-lg font-bold">Regenerate Barcode?</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">This will replace the barcode for this variant. Existing printed labels may no longer match.</p>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('regenerateId', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="regenerate" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">Regenerate</button>
                </div>
            </div>
        </div>
    @endif
</div>
