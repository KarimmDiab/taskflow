@php
    use App\Models\SalesReturn;
    $salesReturn = SalesReturn::with(['invoice.customer', 'invoice.branch', 'items.productVariant.product'])->findOrFail($return);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Return {{ $salesReturn->return_number }}</title>
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
        <h1>RYO RETURN</h1>
        <p>{{ $salesReturn->return_number }}</p>
        <p>Invoice: {{ $salesReturn->invoice?->invoice_number }}</p>
        <p>{{ $salesReturn->created_at?->format('Y-m-d H:i') }}</p>
    </div>
    <div class="line"></div>
    <div class="row"><span>Customer</span><strong>{{ $salesReturn->invoice?->customer?->customer_name ?? '-' }}</strong></div>
    <div class="row"><span>Status</span><strong>{{ ucfirst($salesReturn->status) }}</strong></div>
    <div class="row"><span>Refund</span><strong>{{ str_replace('_', ' ', $salesReturn->refund_method) }}</strong></div>
    <div class="line"></div>
    <table>
        @foreach ($salesReturn->items as $item)
            <tr>
                <td>{{ $item->productVariant?->product?->product_name ?? '-' }}<br>{{ $item->quantity }} x {{ number_format((float) $item->unit_price, 2) }}</td>
                <td style="text-align:right">{{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <div class="row"><span>Return Amount</span><strong>{{ number_format((float) $salesReturn->return_amount, 2) }}</strong></div>
    <p>Reason: {{ $salesReturn->reason }}</p>
</body>
</html>
