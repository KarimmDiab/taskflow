@php
    use App\Models\ProductVariant;
    use App\Services\BarcodeService;

    $payload = request('labels', '');
    $json = base64_decode(strtr($payload, '-_', '+/')) ?: '[]';
    $labels = collect(json_decode($json, true) ?: [])
        ->map(fn ($label) => ['id' => (int) ($label['id'] ?? 0), 'quantity' => max(1, (int) ($label['quantity'] ?? 1))])
        ->filter(fn ($label) => $label['id'] > 0);

    $variants = ProductVariant::with(['product', 'color', 'size'])
        ->whereIn('id', $labels->pluck('id'))
        ->get()
        ->keyBy('id');

    $barcodeService = app(BarcodeService::class);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Barcode Labels</title>
    <style>
        @page { size: 50mm 30mm; margin: 2mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #111827; font-family: Arial, sans-serif; }
        .actions { padding: 12px; }
        .sheet { display: flex; flex-wrap: wrap; gap: 4mm; padding: 4mm; }
        .label { width: 46mm; height: 26mm; border: 1px solid #d1d5db; border-radius: 3px; padding: 2mm; overflow: hidden; page-break-inside: avoid; background: #fff; }
        .product { font-size: 9px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .meta { display: flex; justify-content: space-between; gap: 2mm; font-size: 7px; color: #4b5563; margin-top: 1mm; }
        .price { font-size: 9px; font-weight: 700; margin-top: 1mm; }
        .barcode { height: 12mm; margin-top: 1mm; }
        @media print {
            .actions { display: none; }
            .sheet { padding: 0; gap: 0; }
            .label { border-color: transparent; break-after: page; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button onclick="window.print()">Print Labels</button>
    </div>
    <main class="sheet">
        @forelse ($labels as $label)
            @php($variant = $variants->get($label['id']))
            @if ($variant && $variant->barcode)
                @for ($i = 0; $i < $label['quantity']; $i++)
                    <section class="label">
                        <div class="product">{{ $variant->product?->product_name ?? 'Product' }}</div>
                        <div class="meta">
                            <span>SKU: {{ $variant->sku ?? '-' }}</span>
                            <span>{{ $variant->color?->color_name ?? '-' }} / {{ $variant->size?->size_name ?? '-' }}</span>
                        </div>
                        <div class="price">{{ number_format((float) $variant->variant_price, 2) }}</div>
                        <div class="barcode">{!! $barcodeService->svg($variant->barcode, 34, 1) !!}</div>
                    </section>
                @endfor
            @endif
        @empty
            <p>No labels selected.</p>
        @endforelse
    </main>
</body>
</html>
