
    {{-- resources/views/livewire/purchase-invoices/create-purchase-invoice.blade.php --}}
<div
    x-data="{}"
    @toast.window="
        const ev = $event.detail[0] ?? $event.detail;
        const msg = ev.message ?? '';
        const type = ev.type ?? 'success';
        window.__showToast && window.__showToast(msg, type);
    "
    class="pi-root"
>

{{-- ══════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════ --}}
<div class="pi-page-header">
    <div class="pi-page-header-inner">
        <div class="pi-page-header-left">
            <div class="pi-header-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="pi-page-title">فاتورة مشتريات جديدة</h1>
                <p class="pi-page-sub">المشتريات &larr; فاتورة جديدة</p>
            </div>
        </div>
        <span class="pi-inv-badge">{{ $invoice_number }}</span>
    </div>
</div>
