<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'fr']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفاتورة</title>
    <style>
        .invoice-container {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            direction: {{ in_array(app()->getLocale(), ['ar', 'fr']) ? 'rtl' : 'ltr' }};
        }

        .invoice-container .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .invoice-container .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .invoice-container .invoice-logo {
            max-width: 150px;
        }

        .invoice-container .invoice-title {
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-container .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-container .invoice-details>div {
            width: 48%;
        }

        .invoice-container .address-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-container .address-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .invoice-container .address-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .invoice-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .invoice-container th,
        .invoice-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: {{ in_array(app()->getLocale(), ['ar', 'fr']) ? 'right' : 'left' }};
        }

        .invoice-container th {
            background-color: #f2f2f2;
        }

        .invoice-container .total-row {
            font-weight: bold;
        }

        .invoice-container .invoice-footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 0.9em;
        }

        @media print {
            .invoice-container {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="container">
            <div class="invoice-header">
                <img src="{{ asset(IMG_LOGO_PATH . allsetting()['main_logo']) }}" alt="Logo" class="invoice-logo">
                <span class="invoice-title">{{ __('Invoice') }}</span>
            </div>

            <div class="invoice-details">
                <div>
                    <p><strong>{{ allsetting()['app_title'] ?? 'هاي سبيد' }}</strong></p>
                    <p>{{ __('Phone') }}: {{ allsetting()['call_us'] ?? '+968 94974726' }}</p>
                    <p>{{ allsetting()['email'] ?? 'alsaraamills@gmail.com' }}</p>
                    <p>{{ url('/') }}</p>
                </div>
                <div>
                    <p>{{ __('Purchase Date') }}: {{ $order->created_at->timezone('Asia/Muscat')->format('d/m/Y') }}</p>
                    <p>{{ __('Purchase Time') }}: {{ $order->created_at->timezone('Asia/Muscat')->format('h:i A') }}</p>
                    <p>{{ __('Order Number') }}: {{ $order->Order_Number }}</p>
                    @if(strtolower($order->Payment_Method) === 'thawani' && $order->is_paid == 0)
                        <p>{{ __('Payment Method') }}: {{ __('Unpaid') }}</p>
                    @else
                        <p>{{ __('Payment Method') }}: {{ $order->Payment_Method }}</p>
                    @endif
                    @if($order->collection_method)
                        @if($order->collection_method === 'store_pickup')
                            <p>{{ __('Collection Method') }}: {{ __('Store Pickup') }}</p>
                        @else
                            <p>{{ __('Collection Method') }}: {{ str_replace('_', ' ', ucfirst($order->collection_method)) }}</p>
                        @endif
                    @endif
                    <p>{{ __('Shipping Method') }}: {{ __('Delivery Charge') }}</p>
                </div>
            </div>


            @php
                $phone = $order->user->Number ?? null;

                if ($phone && preg_match('/^\+?(\d{3})(\d+)/', $phone, $matches)) {
                    $formattedUserPhone = "({$matches[1]}) {$matches[2]}";
                } else {
                    $formattedUserPhone = '--';
                }
            @endphp

            <div class="address-container">
                <div class="address-box">
                    <div class="address-title">{{ __('Invoice To') }}</div>
                    <p>{{ __('Customer Name') }}: {{ $order->billing_address['name'] ?? '--' }}</p>
                    <p>{{ __('Email') }}: {{ $order->billing_address['email'] ?? '--' }}</p>
                    @if ($order->user)
                        <p>{{ __('Phone Number') }}:
                            <span dir="ltr" style="unicode-bidi: plaintext">
                                {{ $formattedUserPhone }}
                            </span>
                        </p>
                    @endif

                </div>
                @php
                    $phone = $order->billing_address['phone_number'] ?? null;

                    if ($phone && preg_match('/^\+?(\d{3})(\d+)/', $phone, $matches)) {
                        $formattedPhone = "({$matches[1]}) {$matches[2]}";
                    } else {
                        $formattedPhone = '--';
                    }
                @endphp
                <div class="address-box">
                    <div class="address-title">{{ __('Ship To') }}</div>
                    <p>{{ __('Address') }}: {{ $order->billing_address['street'] ?? '--' }}</p>
                    <p>{{ __('State') }}: {{ $order->billing_address['state_ar'] ?? '--' }}</p>
                    <p>{{ __('City') }}: {{ $order->billing_address['city_ar'] ?? '--' }}</p>
                    <p>{{ __('Country') }}: سلطنة عمان</p>
                    <p>{{ __('Phone Number') }}:
                        <span dir="ltr" style="unicode-bidi: plaintext">
                            {{ $formattedPhone }}
                        </span>
                    </p>

                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Item') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->order_details as $od)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $od->product->fr_Product_Name }}</td>
                            <td>{{ $od->Quantity }}</td>
                            @if ($order->order_source == 'whatsapp')
                                <td>{{ $od->Size }}</td>

                            @else
                                @php
                                    $size = json_decode($od->Size); 
                                @endphp
                                <td>{{ is_null($size?->weight) ? __('--') : $size?->weight . ' ' . __('grams') }}</td>
                            @endif
                            <td>{{ $od->Price }}</td>
                            <td>{{ $od->Total_Price }} OMR</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="5">{{ __('Subtotal') }}</td>
                        <td>{{ $order->Sub_Total }} {{ __('OMR') }}</td>
                    </tr>
                    @if ($order->Coupon_Amount > 0)
                        <tr>
                            <td colspan="5">{{ __('Discount') }}</td>
                            <td>{{ $order->Coupon_Amount }}</td>
                        </tr>
                        <tr>
                            <td colspan="5">{{ __('Total After Discount') }}</td>
                            <td>{{ $order->Sub_Total - $order->Coupon_Amount }} {{ __('OMR') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5">{{ __('Delivery Charge') }}</td>
                        <td>{{ $order->Delivery_Charge }} {{ __('OMR') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5">{{ __('Grand Total') }}:</td>
                        <td>{{ $order->Sub_Total - $order->Coupon_Amount + $order->Delivery_Charge }}
                            {{ __('OMR') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-footer">
                <p><strong>{{ __('Notes') }}:</strong></p>
            </div>

            <div class="modal-footer invoice-modal-footer">
                <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <a href="{{ route('admin.order.print', $order->id) }}" class="btn btn-info" target="_blank">{{ __('Print') }}</a>
            </div>
        </div>
    </div>
</body>

</html>