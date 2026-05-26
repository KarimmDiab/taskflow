<section class="px-4 py-4 lg:px-6" x-data="{ focusNext(row, field) { this.$nextTick(() => { const target = document.querySelector(`[data-row='${row}'][data-field='${field}']`); if (target) target.focus(); }); }, nextRow(row) { this.$wire.addRow(); this.$nextTick(() => setTimeout(() => { const target = document.querySelector(`[data-row='${row + 1}'][data-field='sku']`); if (target) target.focus(); }, 150)); } }">
    <div class="rounded-md border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-3">
            <div>
                <h2 class="text-base font-bold text-slate-950">Receiving Lines</h2>
                <p class="text-xs text-slate-500">Scan barcode or search SKU, product, color, and size directly.</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
                <span>{{ count($rows) }} lines</span>
                <span>{{ number_format(collect($rows)->sum(fn($r) => (float) ($r['qty'] ?? 0)), 2) }} units</span>
            </div>
        </div>

        @error('rows') <div class="m-4 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">{{ $message }}</div> @enderror

        <div class="max-h-[calc(100vh-310px)] overflow-auto" style="height: 400px;">
            <table class="w-full min-w-[1120px] border-separate border-spacing-0 text-sm">
                <thead class="sticky top-0 z-20 bg-slate-100 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="w-12 border-b border-slate-200 px-3 py-2">#</th>
                        <th class="w-[320px] border-b border-slate-200 px-3 py-2">SKU Search</th>
                        <th class="min-w-[260px] border-b border-slate-200 px-3 py-2">Variant</th>
                        <th class="w-28 border-b border-slate-200 px-3 py-2">Qty</th>
                        <th class="w-32 border-b border-slate-200 px-3 py-2">Cost</th>
                        <th class="w-32 border-b border-slate-200 px-3 py-2">Sell</th>
                        <th class="w-44 border-b border-slate-200 px-3 py-2">Branch</th>
                        <th class="w-32 border-b border-slate-200 px-3 py-2 text-right">Total</th>
                        <th class="w-24 border-b border-slate-200 px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rows as $index => $row)
                        <tr wire:key="erp-row-{{ $row['id'] }}" class="odd:bg-white even:bg-slate-50/60 hover:bg-sky-50/60">
                            <td class="px-3 py-2 align-top font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="relative px-3 py-2 align-top">
                                <input type="text" wire:model.live.debounce.250ms="searchQueries.{{ $index }}" wire:focus="$set('openDropdowns.{{ $index }}', true)" @keydown.enter.prevent="if (($wire.searchResults[{{ $index }}] || [])[0]) { $wire.selectVariantForRow({{ $index }}, $wire.searchResults[{{ $index }}][0].id); focusNext({{ $index }}, 'qty'); }" @keydown.escape.prevent="$wire.closeDropdown({{ $index }})" data-row="{{ $index }}" data-field="sku" class="h-9 w-full rounded-md border border-slate-300 bg-white px-3 font-mono text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200 @error("rows.{$index}.product_id") border-red-400 @enderror" placeholder="Scan or search SKU / barcode / black xl hoodie" autocomplete="off" dir="ltr">
                                <div wire:loading wire:target="searchQueries.{{ $index }}" class="absolute right-5 top-4 text-xs text-slate-400">Searching...</div>

                                @if (($openDropdowns[$index] ?? false) && (count($searchResults[$index] ?? []) > 0 || strlen(trim($searchQueries[$index] ?? '')) > 0))
                                    <div class="absolute left-3 right-3 top-12 z-30 overflow-hidden rounded-md border border-slate-200 bg-white shadow-xl" wire:click.stop>
                                        @forelse($searchResults[$index] ?? [] as $variant)
                                            <button type="button" wire:click="selectVariantForRow({{ $index }}, {{ $variant['id'] }})" class="flex w-full items-center gap-3 border-b border-slate-100 px-3 py-2 text-left hover:bg-slate-50">
                                                <div class="h-10 w-10 overflow-hidden rounded-md border border-slate-200 bg-slate-100">
                                                    @if($variant['image_url'])<img src="{{ $variant['image_url'] }}" alt="" class="h-full w-full object-cover">@else<div class="flex h-full w-full items-center justify-center text-xs font-bold text-slate-400">SKU</div>@endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="truncate font-semibold text-slate-950">{{ $variant['product_name'] }}</div>
                                                    <div class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-slate-500"><span>{{ $variant['color_name'] ?: 'No color' }}</span><span>{{ $variant['size_name'] ?: 'No size' }}</span><span class="font-mono text-slate-700">{{ $variant['sku'] }}</span></div>
                                                </div>
                                                <div class="text-right text-xs"><div class="font-semibold text-slate-900">Stock {{ number_format($variant['stock'], 2) }}</div><div class="text-slate-500">Cost {{ number_format($variant['cost'], 2) }}</div></div>
                                            </button>
                                        @empty
                                            <div class="px-3 py-3 text-center text-sm text-slate-500">No variants found.</div>
                                        @endforelse
                                        <button type="button" wire:click="openAddProductModal({{ $index }}, @js(trim($searchQueries[$index] ?? '')))" class="flex w-full items-center gap-2 bg-slate-950 px-3 py-2 text-left text-sm font-semibold text-white hover:bg-slate-800">Quick create variant</button>
                                    </div>
                                @endif
                                @error("rows.{$index}.product_id") <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-3 py-2 align-top">
                                @if($row['variant_id'])
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 overflow-hidden rounded-md border border-slate-200 bg-slate-100">@if($row['image_url'] ?? null)<img src="{{ $row['image_url'] }}" alt="" class="h-full w-full object-cover">@else<div class="h-full w-full" style="background: {{ $row['color_hex'] ?: '#e2e8f0' }}"></div>@endif</div>
                                        <div class="min-w-0"><div class="truncate font-semibold text-slate-950">{{ $row['product_name'] }}</div><div class="flex flex-wrap items-center gap-2 text-xs text-slate-500"><span>{{ $row['color_name'] ?: 'No color' }}</span><span>{{ $row['size_name'] ?: 'No size' }}</span><span class="font-mono text-slate-700">{{ $row['sku'] ?: $row['product_code'] }}</span><span>Stock {{ number_format((float) ($row['stock'] ?? 0), 2) }}</span></div></div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Select a variant SKU</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 align-top"><input type="number" wire:change="updateRowQty({{ $index }}, $event.target.value)" @keydown.enter.prevent="focusNext({{ $index }}, 'cost')" data-row="{{ $index }}" data-field="qty" value="{{ $row['qty'] }}" min="0.01" step="0.01" class="h-9 w-full rounded-md border border-slate-300 bg-white px-2 text-right text-sm font-semibold focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">@error("rows.{$index}.qty") <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror</td>
                            <td class="px-3 py-2 align-top"><input type="number" wire:change="updateRowCost({{ $index }}, $event.target.value)" @keydown.enter.prevent="nextRow({{ $index }})" data-row="{{ $index }}" data-field="cost" value="{{ $row['cost'] }}" min="0" step="0.01" class="h-9 w-full rounded-md border border-slate-300 bg-white px-2 text-right text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">@error("rows.{$index}.cost") <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror</td>
                            <td class="px-3 py-2 align-top"><input type="number" wire:change="updateRowSell({{ $index }}, $event.target.value)" value="{{ $row['sell'] ?? '' }}" min="0" step="0.01" class="h-9 w-full rounded-md border border-slate-300 bg-white px-2 text-right text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200"></td>
                            <td class="px-3 py-2 align-top"><select wire:change="updateRowBranch({{ $index }}, $event.target.value)" class="h-9 w-full rounded-md border border-slate-300 bg-white px-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200"><option value="">Header branch</option>@foreach($this->branches as $branch)<option value="{{ $branch->id }}" {{ ($row['branch_id'] ?? $branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>@endforeach</select>@error("rows.{$index}.branch_id") <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror</td>
                            <td class="px-3 py-2 text-right align-top font-mono font-bold text-slate-950">{{ number_format(($row['qty'] ?? 0) * ($row['cost'] ?? 0), 2) }}</td>
                            <td class="px-3 py-2 align-top"><div class="flex justify-end gap-1"><button type="button" wire:click="duplicateRow({{ $index }})" title="Duplicate" class="flex h-8 w-8 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-600 hover:bg-slate-50">Copy</button><button type="button" wire:click="removeRow({{ $index }})" title="Remove" class="flex h-8 w-8 items-center justify-center rounded-md border border-red-200 bg-white text-red-600 hover:bg-red-50">X</button></div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3"><button type="button" wire:click="addRow" class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Add line</button><div class="text-xs text-slate-500">Enter selects first match. Enter on cost opens the next row.</div></div>
    </div>
</section>
