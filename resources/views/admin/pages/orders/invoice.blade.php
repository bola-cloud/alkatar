<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'fr']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>الفاتورة</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            /* font-family: 'Firefly', sans-serif;                                                */
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 10px;
            /* Reduced base font size */
        }

        .invoice-container {
            max-width: 1100px;
            /* Increased for landscape */
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .invoice-logo {
            max-width: 80px;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: bold;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .invoice-details>div {
            width: 48%;
        }

        .address-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .address-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 8px;
        }

        .address-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: {{ in_array(app()->getLocale(), ['ar', 'fr']) ? 'right' : 'left' }};
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
        }

        .nowrap {
            white-space: nowrap;
        }

        .invoice-footer {
            border-top: 1px solid #ddd;
            padding-top: 8px;
            font-size: 9px;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="{{ asset(IMG_LOGO_PATH . allsetting()['main_logo']) }}" alt="Logo" class="invoice-logo">
            <span class="invoice-title">{{ __('Invoice') }}</span>
        </div>

        <div class="invoice-details">
            <div>
                <p><strong>{{ allsetting()['app_title'] ?? 'هاي سبيد' }}</strong></p>
                <p>{{ __('Phone') }}: {{ allsetting()['call_us'] ?? '+968 94974726' }}</p>
                <p>{{ allsetting()['email'] ?? 'alsaraamills@gmail.com' }}</p>
                <p>رقم سجل التجاري (C.R No.): 1275107</p>
                <p>اسم سجل التجاري (C.R Name): Al Akeed Lil Injaz</p>
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
                    <p>{{ __('Collection Method') }}: {{ $order->collection_method == 'delivery' ? __('Delivery') : __('Store Pickup') }}</p>
                @endif
                <p>{{ __('Shipping Method') }}: {{ __('Delivery Charge') }}</p>
            </div>
        </div>

        <div class="address-container">

            @php
                $phone = $order->user->Number ?? null;

                if ($phone && preg_match('/^\+?(\d{3})(\d+)/', $phone, $matches)) {
                    $formattedUserPhone = "({$matches[1]}) {$matches[2]}";
                } else {
                    $formattedUserPhone = '--';
                }
            @endphp
            <div class="address-box">
                <div class="address-title">{{ __('Invoice To') }}</div>
                <p>{{ __('Customer Name') }}: {{ $order->billing_address['name'] ?? '--' }}</p>
                <p>{{ __('Email') }}: {{ $order->billing_address['email'] ?? '--' }}</p>
                <p>{{ __('Phone Number') }}: 
                    <span dir="ltr" style="unicode-bidi: plaintext">
                        {{ $order->billing_address['phone_number'] ?? ($order->user->Number ?? '--') }}
                    </span>
                </p>
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
                        {{ $formattedPhone  }}
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
                        <td class="nowrap">{{ $od->Quantity }}</td>
                        @if ($order->order_source == 'whatsapp')
                            <td>{{ $od->Size }}</td>
                        @else
                            @php
                                $size = json_decode($od->Size); 
                            @endphp
                            <td>{{ is_null($size?->weight) ? __('--') : $size?->weight . ' ' . __('grams') }}</td>
                        @endif
                        <td class="nowrap">{{ $od->Price }}</td>
                        <td class="nowrap">{{ $od->Total_Price }} {{ __('OMR') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5">{{ __('Subtotal') }}</td>
                    <td class="nowrap">{{ $order->Sub_Total }} {{ __('OMR') }}</td>
                </tr>
                <tr>
                    <td colspan="5">{{ __('Delivery Charge') }}</td>
                    <td class="nowrap">{{ $order->Delivery_Charge }} {{ __('OMR') }}</td>
                </tr>
                @if ($order->Coupon_Amount > 0)
                    <tr>
                        <td colspan="5">{{ __('Discount') }}</td>
                        <td class="nowrap">{{ $order->Coupon_Amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="5">{{ __('Total After Discount') }}</td>
                        <td class="nowrap">{{ $order->Sub_Total - $order->Coupon_Amount}} {{ __('OMR') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="5">{{ __('Grand Total') }}:</td>
                    <td class="nowrap">{{ $order->Sub_Total - $order->Coupon_Amount + $order->Delivery_Charge }} {{ __('OMR') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p><strong>{{ __('Notes') }}:</strong></p>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            @php
                $billingAddress = $order->billing_address;
                $phoneNumber = is_array($billingAddress) ? ($billingAddress['phone_number'] ?? '--') : ($order->user->Number ?? '--');
                $qrData = "Order:" . $order->id . " | Phone:" . $phoneNumber;

                $qrCodeString = (string) QrCode::size(100)->generate($qrData);
                $svgStart = strpos($qrCodeString, '<svg');
                $cleanQrCode = ($svgStart !== false) ? substr($qrCodeString, $svgStart) : $qrCodeString;
            @endphp
            {!! $cleanQrCode !!}
            <p style="margin-top: 5px; font-size: 10px;">{{ $order->Order_Number }}</p>
        </div>
    </div>
</body>

<script>
    window.onload = function () {
        window.print();
    }
</script>

</html>