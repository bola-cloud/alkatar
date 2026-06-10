<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ __('Delivery Men Report') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 6px; text-align: center; }
        th { background: #eee; }
        .payment-summary { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ __('Delivery Men Report') }}</h2>
        <p>
            {{ __('Period') }}: {{ __($range) }} 
            @if($range === 'custom')
                ({{ $start_date }} - {{ $end_date }})
            @endif
        </p>
    </div>

    <div class="summary-box">
        <h3>{{ __('Invoices Summary by Payment Method') }}</h3>
        <p><strong>{{ __('Total Invoices') }}:</strong> {{ number_format($overallCount) }} {{ __('Orders') }} - {{ number_format($overallTotal, 3) }} OMR</p>
        
        <table class="payment-summary">
            <thead>
                <tr>
                    <th>{{ __('Payment Method') }}</th>
                    <th>{{ __('Orders Count') }}</th>
                    <th>{{ __('Total Amount') }} (OMR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentTotals as $pt)
                    <tr>
                        <td>{{ $pt->Payment_Method ?: __('Unknown') }}</td>
                        <td>{{ number_format($pt->count) }}</td>
                        <td>{{ number_format($pt->total_amount, 3) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Order Number') }}</th>
                <th>{{ __('Delivery Man') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th>{{ __('Delivery Charge') }}</th>
                <th>{{ __('Payment Method') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->Order_Number }}</td>
                    <td>{{ $order->deliveryMan->name ?? __('Unassigned') }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ number_format($order->Grand_Total, 3) }}</td>
                    <td>{{ number_format($order->Delivery_Charge, 3) }}</td>
                    <td>{{ $order->Payment_Method }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">{{ __('No orders found matching the filter.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
