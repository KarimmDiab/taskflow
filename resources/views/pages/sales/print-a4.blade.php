@php
    use App\Models\SalesInvoice;
    $salesInvoice = SalesInvoice::with(['customer', 'branch', 'user', 'paymentMethod', 'salesInvoiceDetails.productVariant.product', 'salesInvoiceDetails.productVariant.color', 'salesInvoiceDetails.productVariant.size'])->findOrFail($invoice);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $salesInvoice->invoice_number }}</title>
    <style>
        @page { size: A4; margin: 14mm; }
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #111827; margin: 0; font-size: 13px; }
        .header { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #111827; padding-bottom: 18px; }
        .brand h1 { margin: 0; font-size: 26px; }
        .muted { color: #6b7280; }
        .badge { display: inline-block; padding: 4px 9px; border-radius: 999px; background: #ecfdf5; color: #047857; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-top: 20px; }
        .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; }
        h2 { margin: 0 0 10px; font-size: 14px; text-transform: uppercase; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; text-align: left; font-size: 11px; text-transform: uppercase; color: #4b5563; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .totals { width: 320px; margin-left: auto; margin-top: 20px; }
        .totals div { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .totals .grand { font-size: 17px; font-weight: 700; border-bottom: 2px solid #111827; }
        .actions { margin-top: 20px; }
        @media print { .actions { display: none; } body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print</button></div>
    <section class="header">
        <div class="brand">
            <h1>RYO ERP</h1>
            <p class="muted">Professional sales invoice</p>
        </div>
        <div>
            <h1>Invoice</h1>
            <p><strong>{{ $salesInvoice->invoice_number }}</strong></p>
            <p>{{ $salesInvoice->created_at?->format('Y-m-d H:i') }}</p>
            <span class="badge">{{ $salesInvoice->status }}</span>
        </div>
    </section>
    <section class="grid">
        <div class="box">
            <h2>Company Info</h2>
            <p><strong>RYO</strong></p>
            <p class="muted">Branch: {{ $salesInvoice->branch?->branch_name ?? '-' }}</p>
            <p class="muted">{{ $salesInvoice->branch?->branch_address ?? '' }}</p>
        </div>
        <div class="box">
            <h2>Customer Info</h2>
            <p><strong>{{ $salesInvoice->customer?->customer_name ?? '-' }}</strong></p>
            <p class="muted">{{ $salesInvoice->customer?->contact_info ?? '' }}</p>
            <p class="muted">Cashier: {{ $salesInvoice->user?->name ?? '-' }}</p>
        </div>
    </section>
    <table>
        <thead><tr><th>Product</th><th>SKU</th><th>Color</th><th>Size</th><th>Qty</th><th>Price</th><th>Discount</th><th>Total</th></tr></thead>
        <tbody>
            @foreach ($salesInvoice->salesInvoiceDetails as $detail)
                <tr>
                    <td>{{ $detail->productVariant?->product?->product_name ?? '-' }}</td>
                    <td>{{ $detail->productVariant?->sku ?? '-' }}</td>
                    <td>{{ $detail->productVariant?->color?->color_name ?? '-' }}</td>
                    <td>{{ $detail->productVariant?->size?->size_name ?? '-' }}</td>
                    <td>{{ $detail->product_quantity }}</td>
                    <td>{{ number_format((float) $detail->unit_price, 2) }}</td>
                    <td>{{ number_format((float) $detail->discount_amount, 2) }}</td>
                    <td>{{ number_format((float) ($detail->line_total ?: $detail->product_quantity * $detail->unit_price), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <section class="totals">
        <div><span>Subtotal</span><strong>{{ number_format((float) $salesInvoice->total_amount, 2) }}</strong></div>
        <div><span>Discount</span><strong>{{ number_format((float) $salesInvoice->deduction, 2) }}</strong></div>
        <div><span>Tax</span><strong>{{ number_format((float) $salesInvoice->tax_amount, 2) }}</strong></div>
        <div class="grand"><span>Grand Total</span><span>{{ number_format((float) $salesInvoice->net_total, 2) }}</span></div>
        <div><span>Paid</span><strong>{{ number_format((float) $salesInvoice->paid_amount, 2) }}</strong></div>
        <div><span>Remaining</span><strong>{{ number_format((float) $salesInvoice->remaining_amount, 2) }}</strong></div>
        <div><span>Payment</span><strong>{{ $salesInvoice->paymentMethod?->payment_method_name ?? '-' }}</strong></div>
    </section>
</body>
</html>
