@php
    use App\Models\SalesInvoice;
    $salesInvoice = SalesInvoice::with(['customer', 'branch', 'paymentMethod', 'salesInvoiceDetails.productVariant.product'])->findOrFail($invoice);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $salesInvoice->invoice_number }}</title>
    <style>
        @page { size: 80mm auto; margin: 4mm; }
        body { font-family: Arial, sans-serif; width: 72mm; margin: 0 auto; color: #111; font-size: 12px; }
        h1, p { margin: 0; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #999; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; gap: 8px; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; border-bottom: 1px dashed #ddd; vertical-align: top; }
        .actions { margin: 10px 0; }
        @media print { .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print</button></div>
    <div class="center">
        <h1>RYO</h1>
        <p>{{ $salesInvoice->branch?->branch_name ?? '-' }}</p>
        <p>{{ $salesInvoice->created_at?->format('Y-m-d H:i') }}</p>
        <p><strong>{{ $salesInvoice->invoice_number }}</strong></p>
    </div>
    <div class="line"></div>
    <div class="row"><span>Customer</span><strong>{{ $salesInvoice->customer?->customer_name ?? '-' }}</strong></div>
    <div class="row"><span>Payment</span><strong>{{ $salesInvoice->paymentMethod?->payment_method_name ?? '-' }}</strong></div>
    <div class="line"></div>
    <table>
        @foreach ($salesInvoice->salesInvoiceDetails as $detail)
            <tr>
                <td>{{ $detail->productVariant?->product?->product_name ?? '-' }}<br>{{ $detail->product_quantity }} x {{ number_format((float) $detail->unit_price, 2) }}</td>
                <td style="text-align:right">{{ number_format((float) ($detail->line_total ?: $detail->product_quantity * $detail->unit_price), 2) }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <div class="row"><span>Subtotal</span><strong>{{ number_format((float) $salesInvoice->total_amount, 2) }}</strong></div>
    @if ($salesInvoice->coupon_code)
        <div class="row"><span>Coupon</span><strong>{{ $salesInvoice->coupon_code }}</strong></div>
    @endif
    <div class="row"><span>Discount</span><strong>{{ number_format((float) $salesInvoice->deduction, 2) }}</strong></div>
    <div class="row"><span>Tax</span><strong>{{ number_format((float) $salesInvoice->tax_amount, 2) }}</strong></div>
    <div class="row"><span>Total</span><strong>{{ number_format((float) $salesInvoice->net_total, 2) }}</strong></div>
    <div class="row"><span>Paid</span><strong>{{ number_format((float) $salesInvoice->paid_amount, 2) }}</strong></div>
    <div class="row"><span>Remaining</span><strong>{{ number_format((float) $salesInvoice->remaining_amount, 2) }}</strong></div>
    <div class="line"></div>
    <p class="center">Thank you for your purchase</p>
</body>
</html>
