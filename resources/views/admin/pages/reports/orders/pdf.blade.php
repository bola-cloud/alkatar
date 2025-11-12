<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .muted {
            color: #666;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .summary {
            margin: 10px 0;
        }

        .summary div {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3 style="margin:0">Orders Report</h3>
        <div class="muted">Generated at: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="summary">
        <div><strong>Total Orders:</strong> {{ number_format($totals['orders_count'] ?? 0) }}</div>
        <div><strong>Total Order Amount:</strong> {{ number_format($totals['order_amount'] ?? 0, 3) }}</div>
    </div>

    @include('admin.pages.reports.orders.partials._table', ['orders' => $orders, 'fieldMap' => $fieldMap])
</body>

</html>