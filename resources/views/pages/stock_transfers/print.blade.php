@php
    use App\Models\StockTransfer;
    $stockTransfer = StockTransfer::with([
        'fromBranch',
        'toBranch',
        'createdBy',
        'sentBy',
        'receivedBy',
        'items.productVariant.product',
        'items.productVariant.color',
        'items.productVariant.size',
    ])->findOrFail($transfer);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Stock Transfer {{ $stockTransfer->transfer_number }}</title>
    <style>
        @page { size: A4; margin: 14mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #111827; font-family: Arial, sans-serif; font-size: 13px; }
        .actions { margin-bottom: 16px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #111827; padding-bottom: 16px; }
        h1, h2, p { margin: 0; }
        .muted { color: #6b7280; }
        .badge { display: inline-block; margin-top: 8px; padding: 4px 9px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 18px; }
        .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; min-height: 90px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; color: #4b5563; font-size: 11px; text-transform: uppercase; text-align: left; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px; }
        .signatures { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 36px; }
        .signature { border-top: 1px solid #111827; padding-top: 8px; text-align: center; color: #4b5563; }
        @media print { .actions { display: none; } body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print</button></div>

    <section class="header">
        <div>
            <h1>Stock Transfer</h1>
            <p class="muted">Internal branch inventory movement document</p>
        </div>
        <div style="text-align:right">
            <h2>{{ $stockTransfer->transfer_number }}</h2>
            <p>{{ $stockTransfer->transfer_date?->format('Y-m-d') }}</p>
            <span class="badge">{{ $stockTransfer->status }}</span>
        </div>
    </section>

    <section class="grid">
        <div class="box">
            <h2>From Branch</h2>
            <p><strong>{{ $stockTransfer->fromBranch?->branch_name ?? '-' }}</strong></p>
            <p class="muted">{{ $stockTransfer->fromBranch?->branch_address ?? '' }}</p>
        </div>
        <div class="box">
            <h2>To Branch</h2>
            <p><strong>{{ $stockTransfer->toBranch?->branch_name ?? '-' }}</strong></p>
            <p class="muted">{{ $stockTransfer->toBranch?->branch_address ?? '' }}</p>
        </div>
        <div class="box">
            <h2>Workflow</h2>
            <p>Created by: {{ $stockTransfer->createdBy?->name ?? $stockTransfer->user?->name ?? '-' }}</p>
            <p>Sent by: {{ $stockTransfer->sentBy?->name ?? '-' }} {{ $stockTransfer->sent_at ? 'at '.$stockTransfer->sent_at->format('Y-m-d H:i') : '' }}</p>
            <p>Received by: {{ $stockTransfer->receivedBy?->name ?? '-' }} {{ $stockTransfer->received_at ? 'at '.$stockTransfer->received_at->format('Y-m-d H:i') : '' }}</p>
        </div>
        <div class="box">
            <h2>Notes</h2>
            <p>{{ $stockTransfer->notes ?: '-' }}</p>
            @if ($stockTransfer->cancellation_reason)
                <p>Cancellation: {{ $stockTransfer->cancellation_reason }}</p>
            @endif
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Sent</th>
                <th>Received</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockTransfer->items as $item)
                <tr>
                    <td>{{ $item->productVariant?->product?->product_name ?? '-' }}</td>
                    <td>{{ $item->productVariant?->sku ?? '-' }}</td>
                    <td>{{ $item->productVariant?->color?->color_name ?? '-' }}</td>
                    <td>{{ $item->productVariant?->size?->size_name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->quantity_sent ?? '-' }}</td>
                    <td>{{ $item->quantity_received ?? '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <section class="signatures">
        <div class="signature">Prepared By</div>
        <div class="signature">Released By</div>
        <div class="signature">Received By</div>
    </section>
</body>
</html>
