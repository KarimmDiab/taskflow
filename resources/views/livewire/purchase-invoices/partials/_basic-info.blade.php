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
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                رقم الفاتورة <span class="pi-req">*</span>
            </label>
            <input
                type="text"
                wire:model.live="invoice_number"
                class="pi-input @error('invoice_number') pi-input-err @enderror"
                placeholder="INV-{{ now()->format('Y') }}-00001"
                dir="ltr"
            >
            @error('invoice_number')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Date --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                تاريخ الفاتورة <span class="pi-req">*</span>
            </label>
            <input
                type="date"
                wire:model.live="purchase_invoice_date"
                class="pi-input @error('purchase_invoice_date') pi-input-err @enderror"
                dir="ltr"
            >
            @error('purchase_invoice_date')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Supplier --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3"/></svg>
                المورد <span class="pi-req">*</span>
            </label>
            <select
                wire:model.live="supplier_id"
                class="pi-input pi-select @error('supplier_id') pi-input-err @enderror"
            >
                <option value="">-- اختر المورد --</option>
                @foreach($this->suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Image --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                صورة الفاتورة
                <span class="pi-optional">(اختياري)</span>
            </label>

            @if($imagePreview)
                <div class="pi-img-preview-wrap">
                    <img src="{{ $imagePreview }}" class="pi-img-preview" alt="صورة الفاتورة">
                    <button type="button" wire:click="removeImage" class="pi-img-remove" title="إزالة الصورة">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            @else
                <label class="pi-upload-zone" for="invoice-image-input">
                    <input
                        type="file"
                        id="invoice-image-input"
                        wire:model="product_image"
                        accept="image/*"
                        class="pi-upload-input"
                    >
                    <svg width="24" height="24" fill="none" stroke="var(--tx3)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:auto">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                    </svg>
                    <p class="pi-upload-txt">اضغط لرفع الصورة</p>
                    <p class="pi-upload-hint">PNG، JPG أو WebP حتى 5MB</p>
                    <div wire:loading wire:target="product_image" class="pi-upload-loading">
                        <span class="pi-spinner"></span> جاري الرفع...
                    </div>
                </label>
            @endif
            @error('product_image')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

    </div>{{-- /grid4 --}}
</div>{{-- /card --}}
