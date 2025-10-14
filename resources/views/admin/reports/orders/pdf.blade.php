<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        .muted { color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f8f9fa; }
        .text-end { text-align: right; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .totals { display: flex; gap: 12px; }
        .badge { display: inline-block; padding: 2px 6px; background: #efefef; border-radius: 4px; }
    </style>
    <!-- Note: This PDF shares the same table partial for consistency with the web view. -->
</head>
<body>
    <h1>Orders Report</h1>
    <div class="mb-2 muted">
        Range: {{ strtoupper($filters['range']) }} | From {{ $filters['start']->format('Y-m-d') }} to {{ $filters['end']->format('Y-m-d') }}
    </div>

    <div class="mb-3">
        <strong>Total Orders:</strong> {{ number_format($totals['count']) }}
        &nbsp;|&nbsp;
        <strong>Total Order Amount:</strong> {{ number_format($totals['amount'], 2) }}
        &nbsp;|&nbsp;
        <strong>Total Delivery Amount:</strong> {{ number_format($totals['delivery'], 2) }}
    </div>

    @include('admin.reports.orders.partials._table', ['orders' => $orders, 'fieldMap' => $fieldMap])
</body>
</html>
