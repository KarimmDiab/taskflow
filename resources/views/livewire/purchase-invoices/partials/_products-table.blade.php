{{-- ══════════════════════════════════════════════════════════
     SECTION 2 — PRODUCTS TABLE
══════════════════════════════════════════════════════════ --}}
<div class="pi-card">
    <div class="pi-sec-hd">
        <span class="pi-step">٢</span>
        <div>
            <div class="pi-sec-title">أصناف الفاتورة</div>
            <div class="pi-sec-sub">أضف المنتجات وحدد الكميات والأسعار</div>
        </div>
        <span class="pi-row-count" style="margin-right:auto">{{ count($rows) }}
            {{ count($rows) === 1 ? 'صنف' : 'أصناف' }}</span>
    </div>

    @error('rows')
        <div class="pi-alert-warn" style="margin-bottom:12px">⚠ {{ $message }}</div>
    @enderror

    <div class="pi-table-wrap" style="height: 400px;">
        <table class="pi-table">
            <thead>
                <tr>
                    <th style="width:36px">#</th>
                    <th style="min-width:220px">المنتج</th>
                    <th style="width:88px">الكمية</th>
                    <th style="width:120px">سعر التكلفة</th>
                    <th style="width:120px">سعر البيع</th>
                    <th style="width:140px">الفرع</th>
                    <th style="width:100px">الإجمالي</th>
                    <th style="width:46px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $index => $row)
                    <tr wire:key="row-{{ $row['id'] }}" class="pi-tr">
                        {{-- # --}}
                        <td class="pi-td-num">{{ $index + 1 }}</td>

                        {{-- Product search --}}
                        <td style="position:relative">
                            <div style="position: relative; direction: rtl;">
                                <input type="text" wire:model.live.debounce.400ms="searchQueries.{{ $index }}"
                                    wire:focus="$set('openDropdowns.{{ $index }}', true)"
                                    class="pi-ti @error("rows.{$index}.product_id") pi-ti-err @enderror"
                                    placeholder="ابحث بالاسم أو الكود..." autocomplete="off"
                                    style="padding-right: 10px; padding-left: 90px;">
                                @if ($row['product_id'])
                                    <span class="pi-prod-badge"
                                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; direction: ltr;">
                                        <svg width="10" height="10" fill="none" stroke="currentColor"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        {{ $row['product_code'] }}
                                    </span>
                                @endif
                                {{-- Loading indicator --}}
                                <span wire:loading wire:target="searchQueries" class="pi-search-loading">
                                    <span class="pi-spinner-sm"></span>
                                </span>
                            </div>

                            {{-- Autocomplete dropdown --}}
                            @if (
                                ($openDropdowns[$index] ?? false) &&
                                    (count($searchResults[$index] ?? []) > 0 || strlen(trim($searchQueries[$index] ?? '')) > 0))
                                <div class="pi-dropdown" wire:click.stop>
                                    @forelse($searchResults[$index] ?? [] as $product)
                                        <div class="pi-drop-item"
                                            wire:click="selectProduct({{ $index }}, {{ $product['id'] }})"
                                            wire:key="prod-{{ $product['id'] }}">
                                            <div style="flex:1">
                                                <div class="pi-drop-name">{{ $product['product_name'] }}</div>
                                                <div class="pi-drop-cat">
                                                    {{ $product['category']['category_name'] ?? '' }}</div>
                                            </div>
                                            <span
                                                class="pi-drop-code">{{ $product['product_code'] ? $product['product_code'] : 'PRD-' . str_pad($product['id'], 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    @empty
                                        <div
                                            style="padding:10px 14px; font-size:12px; color:var(--tx3); text-align:center">
                                            لا توجد نتائج</div>
                                    @endforelse

                                    {{-- Add New Product --}}
                                    <div class="pi-drop-add"
                                        wire:click="openAddProductModal({{ $index }}, @js(trim($searchQueries[$index] ?? '')))">
                                        <svg width="14" height="14" fill="none" stroke="currentColor"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 8v8M8 12h8" />
                                        </svg>
                                        إضافة "{{ trim($searchQueries[$index] ?? '') }}" كمنتج جديد
                                    </div>
                                </div>
                            @endif

                            @error("rows.{$index}.product_id")
                                <span class="pi-err-msg">⚠ {{ $message }}</span>
                            @enderror
                        </td>

                        {{-- Quantity --}}
                        <td>
                            <input type="number" wire:change="updateRowQty({{ $index }}, $event.target.value)"
                                class="pi-ti pi-ti-sm @error("rows.{$index}.qty") pi-ti-err @enderror"
                                value="{{ $row['qty'] }}" min="0.01" step="0.01">
                            @error("rows.{$index}.qty")
                                <span class="pi-err-msg" style="font-size:10px">⚠ {{ $message }}</span>
                            @enderror
                        </td>

                        {{-- Cost Price --}}
                        <td>
                            <div style="position:relative">
                                <input type="number"
                                    wire:change="updateRowCost({{ $index }}, $event.target.value)"
                                    class="pi-ti @error("rows.{$index}.cost") pi-ti-err @enderror"
                                    value="{{ $row['cost'] }}" min="0" step="0.01"
                                    style="padding-left:34px">
                                <span class="pi-curr">ج.م</span>
                            </div>
                            @error("rows.{$index}.cost")
                                <span class="pi-err-msg" style="font-size:10px">⚠ {{ $message }}</span>
                            @enderror
                        </td>

                        {{-- Selling Price --}}
                        <td>
                            <div style="position:relative">
                                <input type="number"
                                    wire:change="updateRowSell({{ $index }}, $event.target.value)"
                                    class="pi-ti" value="{{ $row['sell'] ?? '' }}" min="0" step="0.01"
                                    placeholder="—" style="padding-left:34px">
                                <span class="pi-curr">ج.م</span>
                            </div>
                        </td>


                        {{-- Row Total --}}
                        <td>
                            <span class="pi-tot-cell">
                                {{ number_format(($row['qty'] ?? 0) * ($row['cost'] ?? 0), 2) }}
                            </span>
                        </td>


                        {{-- Branch Selection --}}
                        <td>
                            <select
                                wire:change="updateRowBranch({{ $index }}, $event.target.value)"
                                class="pi-ti @error("rows.{$index}.branch_id") pi-ti-err @enderror"
                                style="width: 100%; padding: 8px 10px; font-size: 13px;">
                                <option value="">-- اختر الفرع --</option>
                                @foreach($this->branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($row['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error("rows.{$index}.branch_id")
                                <span class="pi-err-msg" style="font-size:10px">⚠ {{ $message }}</span>
                            @enderror
                        </td>

                        {{-- Remove --}}
                        <td>
                            <button type="button" wire:click="removeRow({{ $index }})" class="pi-btn-rm"
                                title="حذف الصنف">
                                <svg width="13" height="13" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="pi-empty-state">
                            <div class="pi-empty-icon">
                                <svg width="22" height="22" fill="none" stroke="var(--tx3)"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path
                                        d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16" />
                                </svg>
                            </div>
                            <p>لا توجد أصناف — اضغط "إضافة صنف جديد"</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <button type="button" wire:click="addRow" class="pi-btn-add-row">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        إضافة صنف جديد
    </button>
</div>{{-- /card --}}
