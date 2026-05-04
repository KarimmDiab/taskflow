{{-- ══════════════════════════════════════════════════════════
     SECTION 3 — SUMMARY + PAYMENT
══════════════════════════════════════════════════════════ --}}
<div class="pi-bottom-grid">

    {{-- Summary Cards --}}
    <div class="pi-sum-cards">
        <div class="pi-sum-card">
            <div class="pi-sum-lbl">إجمالي الفاتورة</div>
            <div class="pi-sum-val pi-sum-acc">{{ number_format($this->invoiceTotal, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
        <div class="pi-sum-card">
            <div class="pi-sum-lbl">المبلغ المدفوع</div>
            <div class="pi-sum-val pi-sum-grn">{{ number_format($paid_amount, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
        <div class="pi-sum-card pi-sum-card-remaining {{ $this->remainingAmount > 0 ? 'pi-sum-card-warn' : 'pi-sum-card-ok' }}">
            <div class="pi-sum-lbl">المتبقي</div>
            <div class="pi-sum-val {{ $this->remainingAmount > 0 ? 'pi-sum-yel' : 'pi-sum-grn' }}">{{ number_format($this->remainingAmount, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
    </div>

    {{-- Payment Panel --}}
    <div class="pi-card pi-payment-card">
        <div class="pi-sec-mini-title">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
            بيانات الدفع
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">إجمالي الفاتورة</label>
            <div class="pi-readonly-field">{{ number_format($this->invoiceTotal, 2) }} ج.م</div>
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">طريقة الدفع <span class="pi-req">*</span></label>
            <select wire:model.live="payment_method" class="pi-input pi-select">
                <option value="cash">نقدي</option>
                <option value="bank_transfer">تحويل بنكي</option>
                <option value="check">شيك</option>
                <option value="credit">آجل</option>
            </select>
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">المبلغ المدفوع <span class="pi-req">*</span></label>
            <div style="position:relative">
                <input
                    type="number"
                    wire:model.live="paid_amount"
                    class="pi-input @error('paid_amount') pi-input-err @enderror"
                    min="0" step="0.01"
                    placeholder="0.00"
                    style="padding-left:42px"
                    dir="ltr"
                >
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
            </div>
            @error('paid_amount')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        <div class="pi-field">
            <label class="pi-label">المتبقي</label>
            <div class="pi-remaining-field {{ $this->remainingAmount > 0 ? 'pi-rem-yel' : 'pi-rem-grn' }}">
                {{ number_format($this->remainingAmount, 2) }} ج.م
            </div>
        </div>
    </div>

</div>{{-- /bottom-grid --}}

{{-- ══════════════════════════════════════════════════════════
     ACTION FOOTER
══════════════════════════════════════════════════════════ --}}
<div class="pi-card pi-footer-card">
    <div class="pi-footer-inner">
        <div class="pi-footer-meta">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            آخر تحديث: الآن
        </div>
        <div class="pi-footer-btns">
            <a href="{{ route('purchaseInvoices') }}" wire:navigate class="pi-btn-sec">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                إلغاء
            </a>
            <button
                type="button"
                wire:click="saveInvoice"
                wire:loading.attr="disabled"
                class="pi-btn-pri"
            >
                <span wire:loading.remove wire:target="saveInvoice">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    حفظ الفاتورة
                </span>
                <span wire:loading wire:target="saveInvoice">
                    <span class="pi-spinner"></span>
                    جاري الحفظ...
                </span>
            </button>
        </div>
    </div>
</div>
