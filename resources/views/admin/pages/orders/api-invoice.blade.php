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
            <span class="invoice-title">الفاتورة</span>
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
                        <p style="line-height: 1.5;">تاريخ الشراء: {{ date('d/m/Y', strtotime($order->created_at)) }}
                        </p>
                        <p style="line-height: 1.5;">وقت الشراء:
                            {{ $order->created_at->timezone('Asia/Muscat')->format('h:i A') }}
                        </p>
                        <p style="line-height: 1.5;">رقم الطلب: {{ $order->Order_Number }}</p>
                        <p style="line-height: 1.5;">طريقة الدفع: {{ $order->Payment_Method }}</p>
                        <p style="line-height: 1.5;">طريقة الشحن: مصاريف الشحن</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="address-container" style="margin-bottom: 20px;">
            <table width="100%" style="border: 1px solid #ddd; border-collapse: collapse;">
                <tr>
                    <td style="text-align: right; border: 1px solid #ddd;">
                        <div class="address-title" style="font-weight: bold;">الفاتورة الى</div>
                        <p style="line-height: 1.5;">اسم العميل: {{ $order->billing_address['name'] ?? 'N/A' }}</p>
                        <p style="line-height: 1.5;">البريد الاكتروني: {{ $order->billing_address['email'] ?? 'N/A' }}
                        </p>
                        @if ($order->user)
                            <p style="line-height: 1.5;">رقم الهاتف:
                                {{ $order->user->code . $order->user->Number ?? 'N/A' }}
                            </p>
                        @endif
                    </td>
                    <td style="text-align: right; vertical-align: top; padding: 10px; border: 1px solid #ddd;">
                        <div class="address-title" style="font-weight: bold;">الشحن الى</div>
                        <p style="line-height: 1.5;">العنوان: {{ $order->billing_address['street'] ?? 'N/A' }}</p>
                        <p style="line-height: 1.5;">المحافظة: {{ $order->billing_address['state_ar'] ?? 'N/A' }}</p>
                        <p style="line-height: 1.5;">المدينة: {{ $order->billing_address['city_ar'] ?? 'N/A' }}</p>
                        <p style="line-height: 1.5;">الدولة: سلطنة عمان</p>
                        <p style="line-height: 1.5;">رقم الهاتف: {{ $order->billing_address['phone_number'] ?? 'N/A' }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الكمية</th>
                    <th>الحجم</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_details as $od)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $od->product->fr_Product_Name }}</td>
                        <td class="nowrap">{{ $od->Quantity }}</td>
                        {{-- <td>{{ is_null($od->Size) ? 'N/A' : $od->Size }}</td> --}}
                        @if ($order->order_source == 'whatsapp')
                            <td>{{ $od->Size }}</td>
                        @else
                            @php
                                $size = json_decode($od->Size);
                            @endphp
                            <td>{{ is_null($size?->weight) ? 'N/A' : $size?->weight }} جرام</td>
                        @endif
                        <td class="nowrap">{{ $od->Price }}</td>
                        <td class="nowrap">{{ $od->Total_Price }} OMR</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5">الإجمالي قبل النهائي</td>
                    <td class="nowrap">{{ $order->Sub_Total }} OMR</td>
                </tr>
                <tr>
                    <td colspan="5">مصاريف الشحن</td>
                    <td class="nowrap">{{ $order->Delivery_Charge }} OMR</td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">المجموع النهائي:</td>
                    <td class="nowrap">{{ $order->Grand_Total }} OMR</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p><strong>الملاحظات:</strong></p>

        </div>
    </div>
</body>

</html>