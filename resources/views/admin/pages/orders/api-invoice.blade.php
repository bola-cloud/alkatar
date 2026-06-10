<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* font-family: 'Firefly', sans-serif;                                                */
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

        .invoice-details table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .invoice-details td {
            padding: 5px;
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
            text-align: right;
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

        html {
            font: 16px/1 'Open Sans', sans-serif;
            overflow: auto;
        }

        body {
            box-sizing: border-box;
            overflow: hidden;
        }

        mpdf-col-q {
            width: auto !important;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="{{ asset(IMG_LOGO_PATH . allsetting()['main_logo']) }}" alt="Logo" class="invoice-logo">
            <span class="invoice-title">{{ __('Invoice') }}</span>
        </div>

        <div class="invoice-details" style="margin-bottom: 20px;">
            <table width="100%" border="0px">
                <tr>
                    <td style="text-align: right;">
                        <p style="line-height: 1.5;"><strong>{{ allsetting()['app_title'] ?? 'هاي سبيد' }}</strong></p>
                        <p style="line-height: 1.5;">{{ __('Phone') }}: {{ allsetting()['call_us'] ?? '+968 94974726' }}
                        </p>
                        <p style="line-height: 1.5;">{{ allsetting()['email'] ?? 'alsaraamills@gmail.com' }}</p>
                        <p style="line-height: 1.5;">{{ url('/') }}</p>
                    </td>
                    <td style="text-align: right;">
                        <p style="line-height: 1.5;">{{ __('Purchase Date') }}: {{ $order->created_at->timezone('Asia/Muscat')->format('d/m/Y') }}
                        </p>
                        <p style="line-height: 1.5;">{{ __('Purchase Time') }}:
                            {{ $order->created_at->timezone('Asia/Muscat')->format('h:i A') }}
                        </p>
                        <p style="line-height: 1.5;">{{ __('Order Number') }}: {{ $order->Order_Number }}</p>
                        @if(strtolower($order->Payment_Method) === 'thawani' && $order->is_paid == 0)
                            <p style="line-height: 1.5;">{{ __('Payment Method') }}: {{ __('Unpaid') }}</p>
                        @else
                            <p style="line-height: 1.5;">{{ __('Payment Method') }}: {{ $order->Payment_Method }}</p>
                        @endif
                        @if($order->collection_method)
                            <p style="line-height: 1.5;">{{ __('Collection Method') }}: {{ $order->collection_method == 'delivery' ? __('Delivery') : __('Store Pickup') }}</p>
                        @endif
                        <p style="line-height: 1.5;">{{ __('Shipping Method') }}: {{ __('Delivery Charge') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="address-container" style="margin-bottom: 20px;">
            <table width="100%" style="border: 1px solid #ddd; border-collapse: collapse;">
                <tr>
                    <td style="text-align: right; border: 1px solid #ddd;">
                        <div class="address-title" style="font-weight: bold;">{{ __('Invoice To') }}</div>
                        <p style="line-height: 1.5;">{{ __('Customer Name') }}: {{ $order->billing_address['name'] ?? '--' }}</p>
                        <p style="line-height: 1.5;">{{ __('Email') }}: {{ $order->billing_address['email'] ?? '--' }}
                        </p>
                        @if ($order->user)
                            <p style="line-height: 1.5;">{{ __('Phone Number') }}:
                                {{ $order->user->code . $order->user->Number ?? '--' }}
                            </p>
                        @endif
                    </td>
                    <td style="text-align: right; vertical-align: top; padding: 10px; border: 1px solid #ddd;">
                        <div class="address-title" style="font-weight: bold;">{{ __('Ship To') }}</div>
                        <p style="line-height: 1.5;">{{ __('Address') }}: {{ $order->billing_address['street'] ?? '--' }}</p>
                        <p style="line-height: 1.5;">{{ __('State') }}: {{ $order->billing_address['state_ar'] ?? '--' }}</p>
                        <p style="line-height: 1.5;">{{ __('City') }}: {{ $order->billing_address['city_ar'] ?? '--' }}</p>
                        <p style="line-height: 1.5;">{{ __('Country') }}: سلطنة عمان</p>
                        <p style="line-height: 1.5;">{{ __('Phone Number') }}: {{ $order->billing_address['phone_number'] ?? '--' }}
                        </p>
                    </td>
                </tr>
            </table>
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
                        {{-- <td>{{ is_null($od->Size) ? '--' : $od->Size }}</td> --}}
                        @if ($order->order_source == 'whatsapp')
                            <td>{{ $od->Size }}</td>
                        @else
                            @php
                                $size = json_decode($od->Size);
                            @endphp
                            <td>{{ is_null($size?->weight) ? '--' : $size?->weight }} جرام</td>
                        @endif
                        <td class="nowrap">{{ $od->Price }}</td>
                        <td class="nowrap">{{ $od->Total_Price }} OMR</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5">{{ __('Subtotal') }}</td>
                    <td class="nowrap">{{ $order->Sub_Total }} OMR</td>
                </tr>
                <tr>
                    <td colspan="5">{{ __('Delivery Charge') }}</td>
                    <td class="nowrap">{{ $order->Delivery_Charge }} OMR</td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">{{ __('Grand Total') }}:</td>
                    <td class="nowrap">{{ $order->Grand_Total }} OMR</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p><strong>{{ __('Notes') }}:</strong></p>

        </div>
    </div>
</body>

</html>