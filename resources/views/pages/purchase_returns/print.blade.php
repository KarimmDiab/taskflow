@php
    $purchaseReturn = \App\Models\PurchaseReturn::query()
        ->with(['supplier', 'purchaseInvoice', 'items.productVariant.product', 'createdBy'])
        ->findOrFail(request()->route('purchaseReturn'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase Return {{ $purchaseReturn->return_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; margin: 0; background: #f8fafc; }
        .page { max-width: 920px; margin: 32px auto; background: white; padding: 40px; border: 1px solid #e2e8f0; }
        .top { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #0f172a; padding-bottom: 20px; }
        h1 { margin: 0; font-size: 28px; }
        .muted { color: #64748b; font-size: 13px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 24px; }
        .box { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; }
        .label { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .value { margin-top: 6px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-top: 28px; font-size: 13px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 11px; text-align: left; }
        th { background: #f1f5f9; color: #475569; text-transform: uppercase; font-size: 11px; }
        .total { margin-top: 24px; text-align: right; font-size: 24px; font-weight: 800; color: #047857; }
        .signatures { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-top: 64px; }
        .line { border-top: 1px solid #0f172a; padding-top: 10px; text-align: center; font-size: 13px; color: #64748b; }
        .actions { max-width: 920px; margin: 20px auto 0; text-align: right; }
        button { border: 0; background: #0f172a; color: white; border-radius: 8px; padding: 10px 16px; font-weight: 700; cursor: pointer; }
        @media print { body { background: white; } .page { margin: 0; max-width: none; border: 0; } .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print / Save PDF</button></div>
    <main class="page">
        <section class="top">
            <div>
                <h1>Purchase Return</h1>
                <p class="muted">Inventory and supplier-account adjustment document.</p>
            </div>
            <div style="text-align:right">
                <div class="label">Return Number</div>
                <div class="value">{{ $purchaseReturn->return_number }}</div>
                <p class="muted">{{ $purchaseReturn->return_date?->format('Y-m-d') }}</p>
            </div>
        </section>

        <section class="grid">
            <div class="box"><div class="label">Supplier</div><div class="value">{{ $purchaseReturn->supplier?->supplier_name ?? '-' }}</div></div>
            <div class="box"><div class="label">Original Invoice</div><div class="value">{{ $purchaseReturn->purchaseInvoice?->invoice_number ?? '-' }}</div></div>
            <div class="box"><div class="label">Created By</div><div class="value">{{ $purchaseReturn->createdBy?->name ?? '-' }}</div></div>
            <div class="box"><div class="label">Created At</div><div class="value">{{ $purchaseReturn->created_at?->format('Y-m-d H:i') }}</div></div>
        </section>

        <table>
            <thead><tr><th>Product</th><th>Quantity</th><th>Unit Cost</th><th>Total</th><th>Reason</th></tr></thead>
            <tbody>
                @foreach ($purchaseReturn->items as $item)
                    <tr>
                        <td>{{ $item->productVariant?->product?->product_name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format((float) $item->unit_cost, 2) }}</td>
                        <td>{{ number_format((float) $item->total, 2) }}</td>
                        <td>{{ $item->reason ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">Return Total: EGP {{ number_format((float) $purchaseReturn->total_amount, 2) }}</div>

        <section class="signatures">
            <div class="line">Prepared By</div>
            <div class="line">Supplier Signature</div>
        </section>
    </main>
</body>
</html>
