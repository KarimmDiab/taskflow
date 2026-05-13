{{-- ══════════════════════════════════════════════════════════
     SECTION 1 — INVOICE BASIC INFO
══════════════════════════════════════════════════════════ --}}
<div class="pi-card">
    <div class="pi-sec-hd">
        <span class="pi-step">١</span>
        <div>
            <div class="pi-sec-title">بيانات الفاتورة الأساسية</div>
            <div class="pi-sec-sub">معلومات الفاتورة والمورد</div>
        </div>
    </div>

    <div class="pi-grid4">

        {{-- Invoice Number --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
                رقم الفاتورة <span class="pi-req">*</span>
            </label>
            <input type="text" wire:model.live="invoice_number"
                class="pi-input @error('invoice_number') pi-input-err @enderror"
                placeholder="INV-{{ now()->format('Y') }}-00001" dir="ltr">
            @error('invoice_number')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Date --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <path d="M16 2v4M8 2v4M3 10h18" />
                </svg>
                تاريخ الفاتورة <span class="pi-req">*</span>
            </label>
            <input type="date" wire:model.live="purchase_invoice_date"
                class="pi-input @error('purchase_invoice_date') pi-input-err @enderror" dir="ltr">
            @error('purchase_invoice_date')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Supplier --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3" />
                </svg>
                المورد <span class="pi-req">*</span>
            </label>
            <select wire:model.live="supplier_id"
                class="pi-input pi-select @error('supplier_id') pi-input-err @enderror">
                <option value="">-- اختر المورد --</option>
                @foreach ($this->suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Image Upload --}}
        <div class="pi-field">
            <label class="pi-label">صورة الفاتورة <span class="pi-optional">(اختياري)</span></label>

            <div class="pi-image-upload" x-data="{ isDragging: false }" @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="isDragging = false; $event.dataTransfer.files.length && $wire.upload('invoice_image', $event.dataTransfer.files[0])">

                @if (!$imagePreview)
                    <div class="pi-upload-area"
                        style="border: 2px dashed var(--border); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer;"
                        :style="isDragging ? 'border-color: var(--primary); background: var(--primary-light);' : ''"
                        onclick="document.getElementById('invoice-image-input').click()">
                        <input type="file" id="invoice-image-input" wire:model.live="invoice_image" accept="image/*"
                            style="display: none;">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" style="color: var(--tx3); margin-bottom: 12px;">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" />
                            <circle cx="8.5" cy="8.5" r="2.5" />
                            <path d="M21 15l-5-4-3 3-4-4-6 6" />
                        </svg>
                        <div style="font-size: 14px; font-weight: 500; color: var(--tx); margin-bottom: 4px;">اسحب
                            الصورة أو انقر لاختيارها</div>
                        <div style="font-size: 11px; color: var(--tx3);">PNG, JPG, JPEG (الحد الأقصى 5MB)</div>
                    </div>
                @else
                    <div class="pi-image-preview"
                        style="position: relative; border-radius: 12px; overflow: hidden; background: var(--bg2);">
                        <img src="{{ $imagePreview }}" alt="معاينة الفاتورة"
                            style="width: 100%; max-height: 200px; object-fit: contain;">
                        <div style="position: absolute; top: 10px; left: 10px; display: flex; gap: 8px;">
                            <button type="button" wire:click="removeImage" class="pi-btn-icon" title="حذف الصورة"
                                style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
                                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M18 6L6 18M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @if ($imageName)
                            <div
                                style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); padding: 4px 10px; border-radius: 20px; font-size: 11px; color: white;">
                                {{ $imageName }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @error('invoice_image')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        <style>
            .pi-image-upload {
                transition: all 0.2s ease;
            }

            .pi-btn-icon {
                background: none;
                border: none;
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .pi-btn-icon:hover {
                background: rgba(0, 0, 0, 0.9);
                transform: scale(1.05);
            }
        </style>

    </div>{{-- /grid4 --}}
</div>{{-- /card --}}
