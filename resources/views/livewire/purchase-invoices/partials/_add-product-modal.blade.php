{{-- ══════════════════════════════════════════════════════════
     MODAL — ADD NEW PRODUCT
══════════════════════════════════════════════════════════ --}}
@if ($showModal)
    <div class="pi-modal-bg" x-data x-init="$el.style.opacity = 0;
    requestAnimationFrame(() => {
        $el.style.transition = 'opacity .2s';
        $el.style.opacity = 1;
    })" wire:click.self="closeModal"
        @keydown.escape.window="$wire.closeModal()">
        <div class="pi-modal" x-data x-init="$el.style.transform = 'scale(0.93)';
        $el.style.opacity = 0;
        requestAnimationFrame(() => {
            $el.style.transition = 'all .22s cubic-bezier(.34,1.56,.64,1)';
            $el.style.transform = 'scale(1)';
            $el.style.opacity = 1;
        })" wire:click.stop>
            {{-- Modal Header --}}
            <div class="pi-modal-hd">
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="pi-modal-icon">
                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--tx)">إضافة منتج جديد</div>
                        <div style="font-size:11px;color:var(--tx3)">سيتم إضافته في الصف الحالي تلقائياً</div>
                    </div>
                </div>
                <button type="button" wire:click="closeModal" class="pi-modal-close">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="pi-modal-body">
                <div class="pi-grid2">

                    {{-- Product Name --}}
                    <div class="pi-field pi-col-full">
                        <label class="pi-label">اسم المنتج <span class="pi-req">*</span></label>
                        <input type="text" wire:model.live="newProductName"
                            class="pi-input @error('newProductName') pi-input-err @enderror"
                            placeholder="مثال: لاب توب HP ProBook 450" autofocus>
                        @error('newProductName')
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Product Code --}}
                    <div class="pi-field">
                        <label class="pi-label">كود المنتج <span class="pi-req">*</span></label>
                        <input type="text" wire:model.live="newProductCode"
                            class="pi-input @error('newProductCode') pi-input-err @enderror" placeholder="HP-001"
                            dir="ltr">
                        @error('newProductCode')
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="pi-field">
                        <label class="pi-label">التصنيف <span class="pi-req">*</span></label>
                        <select wire:model.live="newProductCategoryId"
                            class="pi-input pi-select @error('newProductCategoryId') pi-input-err @enderror">
                            <option value="">-- اختر التصنيف --</option>
                            @foreach ($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        @error('newProductCategoryId')
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Sub Category --}}
                    <div class="pi-field">
                        <label class="pi-label">التصنيف الفرعي <span class="pi-optional">(اختياري)</span></label>
                        <select wire:model.live="newProductSubCategoryId"
                            class="pi-input pi-select @error('newProductSubCategoryId') pi-input-err @enderror">
                            <option value="">-- اختر التصنيف الفرعي --</option>
                            @foreach ($this->subCategories as $subCat)
                                <option value="{{ $subCat->id }}">{{ $subCat->sub_category_name }}</option>
                            @endforeach
                        </select>
                        @error('newProductSubCategoryId')
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Cost Price --}}
                    <div class="pi-field">
                        <label class="pi-label">سعر التكلفة <span class="pi-req">*</span></label>
                        <div style="position:relative">
                            <input type="number" wire:model.live="newProductCost"
                                class="pi-input @error('newProductCost') pi-input-err @enderror" min="0"
                                step="0.01" placeholder="0.00" style="padding-left:42px" dir="ltr">
                            <span
                                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
                        </div>
                        @error('newProductCost')
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Selling Price --}}
                    <div class="pi-field">
                        <label class="pi-label">
                            سعر البيع
                            <span class="pi-optional">(اختياري)</span>
                        </label>
                        <div style="position:relative">
                            <input type="number" wire:model.live="newProductSell" class="pi-input" min="0"
                                step="0.01" placeholder="0.00" style="padding-left:42px" dir="ltr">
                            <span
                                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
                        </div>
                    </div>

                </div>

                {{-- Info hint --}}
                <div class="pi-hint-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                    <span class="pi-hint-txt">بعد الحفظ سيتم إضافة المنتج تلقائياً في الصف الحالي بالفاتورة وتحديث
                        الكميات.</span>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="pi-modal-ft">
                <button type="button" wire:click="closeModal" class="pi-btn-sec"
                    style="padding:8px 16px;font-size:12px">إلغاء</button>
                <button type="button" wire:click="saveNewProduct" wire:loading.attr="disabled" class="pi-btn-pri"
                    style="padding:8px 16px;font-size:12px">
                    <span wire:loading.remove wire:target="saveNewProduct">
                        <svg width="13" height="13" fill="none" stroke="white" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        حفظ المنتج
                    </span>
                    <span wire:loading wire:target="saveNewProduct">
                        <span class="pi-spinner"></span>
                        جاري الحفظ...
                    </span>
                </button>
            </div>
        </div>
    </div>
@endif

</div>{{-- /pi-root --}}
