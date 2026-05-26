<div
    x-data="{}"
    @toast.window="
        const ev = $event.detail[0] ?? $event.detail;
        const msg = ev.message ?? '';
        const type = ev.type ?? 'success';
        window.__showToast && window.__showToast(msg, type);
    "
    class="min-h-screen bg-slate-50 text-slate-900"
>
<div class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto max-w-[1800px] px-4 py-3 lg:px-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-md bg-slate-950 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7h18M6 7v13h12V7M9 7V4h6v3"/></svg>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Variant-first receiving</div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-950">{{ $invoice_number }}</h1>
                        <span class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">Draft</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('purchaseInvoices') }}" wire:navigate class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                <button type="button" class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Save Draft</button>
                <button type="button" wire:click="saveInvoice" wire:loading.attr="disabled" class="inline-flex h-9 items-center gap-2 rounded-md bg-slate-950 px-4 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveInvoice">Confirm Invoice</span>
                    <span wire:loading wire:target="saveInvoice">Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
