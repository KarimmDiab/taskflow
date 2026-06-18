@php
    $payment = \App\Models\SupplierPayment::query()
        ->with(['supplier', 'purchaseInvoice', 'paymentMethod', 'createdBy'])
        ->findOrFail(request()->route('supplierPayment'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payment Voucher {{ $payment->payment_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; margin: 0; background: #f8fafc; }
        .page { max-width: 820px; margin: 32px auto; background: white; padding: 40px; border: 1px solid #e2e8f0; }
        .top { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #0f172a; padding-bottom: 20px; }
        h1 { margin: 0; font-size: 28px; }
        .muted { color: #64748b; font-size: 13px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 28px; }
        .box { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; }
        .label { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .value { margin-top: 6px; font-weight: 700; }
        .amount { margin: 28px 0; padding: 22px; border-radius: 14px; background: #ecfdf5; color: #047857; font-size: 30px; font-weight: 800; text-align: center; }
        .notes { min-height: 70px; }
        .signatures { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-top: 64px; }
        .line { border-top: 1px solid #0f172a; padding-top: 10px; text-align: center; font-size: 13px; color: #64748b; }
        .actions { max-width: 820px; margin: 20px auto 0; text-align: right; }
        button { border: 0; background: #0f172a; color: white; border-radius: 8px; padding: 10px 16px; font-weight: 700; cursor: pointer; }
        @media print { body { background: white; } .page { margin: 0; max-width: none; border: 0; } .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print / Save PDF</button></div>
    <main class="page">
        <section class="top">
            <div>
                <h1>Supplier Payment Voucher</h1>
                <p class="muted">Accounting record. No edits or deletes are permitted.</p>
            </div>
            <div style="text-align:right">
                <div class="label">Payment Number</div>
                <div class="value">{{ $payment->payment_number }}</div>
                <p class="muted">{{ $payment->payment_date?->format('Y-m-d') }}</p>
            </div>
        </section>

        <div class="amount">EGP {{ number_format((float) $payment->amount, 2) }}</div>

        <section class="grid">
            <div class="box"><div class="label">Supplier</div><div class="value">{{ $payment->supplier?->supplier_name ?? '-' }}</div></div>
            <div class="box"><div class="label">Invoice Number</div><div class="value">{{ $payment->purchaseInvoice?->invoice_number ?? 'Unallocated' }}</div></div>
            <div class="box"><div class="label">Payment Method</div><div class="value">{{ $payment->paymentMethod?->payment_method_name ?? '-' }}</div></div>
            <div class="box"><div class="label">Reference Number</div><div class="value">{{ $payment->reference_number ?: '-' }}</div></div>
            <div class="box"><div class="label">Created By</div><div class="value">{{ $payment->createdBy?->name ?? '-' }}</div></div>
            <div class="box"><div class="label">Created At</div><div class="value">{{ $payment->created_at?->format('Y-m-d H:i') }}</div></div>
            <div class="box notes" style="grid-column: 1 / -1;"><div class="label">Notes</div><div class="value">{{ $payment->notes ?: '-' }}</div></div>
        </section>

        <section class="signatures">
            <div class="line">Prepared By</div>
            <div class="line">Supplier Signature</div>
        </section>
    </main>
</body>
</html>
