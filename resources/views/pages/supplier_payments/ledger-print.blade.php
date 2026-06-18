@php
    $supplierId = request()->integer('supplier_id') ?: null;
    $dateFrom = request('date_from');
    $dateTo = request('date_to');
    $supplier = $supplierId ? \App\Models\Supplier::find($supplierId) : null;

    $invoiceQuery = \App\Models\PurchaseInvoice::query()->with('supplier')->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId));
    $paymentQuery = \App\Models\SupplierPayment::query()->with(['supplier', 'purchaseInvoice'])->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId));

    if ($dateFrom) {
        $invoiceQuery->whereDate('purchase_invoice_date', '>=', $dateFrom);
        $paymentQuery->whereDate('payment_date', '>=', $dateFrom);
    }
    if ($dateTo) {
        $invoiceQuery->whereDate('purchase_invoice_date', '<=', $dateTo);
        $paymentQuery->whereDate('payment_date', '<=', $dateTo);
    }

    $rows = collect()
        ->merge($invoiceQuery->get()->map(fn ($invoice) => ['date' => $invoice->purchase_invoice_date, 'type' => 'Invoice', 'reference' => $invoice->invoice_number, 'supplier' => $invoice->supplier?->supplier_name, 'debit' => (float) $invoice->total_amount, 'credit' => 0.0]))
        ->merge($paymentQuery->get()->map(fn ($payment) => ['date' => $payment->payment_date, 'type' => 'Payment', 'reference' => $payment->payment_number . ($payment->purchaseInvoice ? ' / ' . $payment->purchaseInvoice->invoice_number : ''), 'supplier' => $payment->supplier?->supplier_name, 'debit' => 0.0, 'credit' => (float) $payment->amount]))
        ->sortBy([['date', 'asc'], ['type', 'asc']])
        ->values();

    $balance = 0;
    $rows = $rows->map(function ($row) use (&$balance) {
        $balance += $row['debit'] - $row['credit'];
        $row['balance'] = $balance;
        return $row;
    });
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Supplier Ledger</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; background: #f8fafc; margin: 0; }
        .page { max-width: 1100px; margin: 28px auto; background: white; padding: 32px; border: 1px solid #e2e8f0; }
        .top { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #0f172a; padding-bottom: 18px; }
        h1 { margin: 0; font-size: 26px; }
        .muted { color: #64748b; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; font-size: 12px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #f1f5f9; color: #475569; text-transform: uppercase; font-size: 11px; }
        .actions { max-width: 1100px; margin: 18px auto 0; text-align: right; }
        button { border: 0; background: #0f172a; color: white; border-radius: 8px; padding: 10px 16px; font-weight: 700; cursor: pointer; }
        @media print { body { background: white; } .page { margin: 0; max-width: none; border: 0; } .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Print / Save PDF</button></div>
    <main class="page">
        <section class="top">
            <div>
                <h1>Supplier Ledger</h1>
                <p class="muted">{{ $supplier?->supplier_name ?? 'All suppliers' }}</p>
            </div>
            <div class="muted" style="text-align:right">
                <div>From: {{ $dateFrom ?: 'Beginning' }}</div>
                <div>To: {{ $dateTo ?: 'Today' }}</div>
                <div>Printed: {{ now()->format('Y-m-d H:i') }}</div>
            </div>
        </section>
        <table>
            <thead><tr><th>Date</th><th>Type</th><th>Reference</th><th>Supplier</th><th>Debit</th><th>Credit</th><th>Running Balance</th></tr></thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ optional($row['date'])->format('Y-m-d') }}</td>
                        <td>{{ $row['type'] }}</td>
                        <td>{{ $row['reference'] }}</td>
                        <td>{{ $row['supplier'] }}</td>
                        <td>{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '-' }}</td>
                        <td>{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '-' }}</td>
                        <td><strong>{{ number_format($row['balance'], 2) }}</strong></td>
                    </tr>
                @empty
                    <tr><td colspan="7">No ledger transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
