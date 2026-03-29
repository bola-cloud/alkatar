<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>الفاتورة - {{ $order->Order_Number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* DejaVu Sans supports Arabic in mPDF */
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 11px;
            direction: rtl;
            text-align: right;
        }
        .invoice-container {
            padding: 20px;
        }
        .header-table, .details-table, .address-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            max-width: 100px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .address-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }
        .address-title {
            font-weight: bold;
            background: #f9f9f9;
            padding: 5px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .text-right { text-align: right !important; }
        .text-left { text-align: left !important; }
        .total-row td {
            font-weight: bold;
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="text-right">
                    @php
                        $logoPath = public_path('uploaded_files/logo/' . allsetting()['main_logo']);
                    @endphp
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo" alt="Logo">
                    @endif
                </td>
            </tr>
            <tr>
                <td class="invoice-title">الفاتورة</td>
            </tr>
        </table>

        <!-- Summary Details -->
        <table class="details-table">
            <tr>
                <td width="50%">
                    <p><strong>{{ allsetting()['app_title'] ?? 'هاي سبيد' }}</strong></p>
                    <p>{{ __('Phone') }}: {{ allsetting()['call_us'] ?? '+968 94974726' }}</p>
                    <p>{{ allsetting()['email'] ?? 'alsaraamills@gmail.com' }}</p>
                </td>
                <td width="50%" class="text-left">
                    <p>رقم الطلب: {{ $order->Order_Number }}</p>
                    <p>التاريخ: {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                    <p>الوقت: {{ $order->created_at->timezone('Asia/Muscat')->format('h:i A') }}</p>
                    <p>الدفع: {{ $order->Payment_Method }}</p>
                </td>
            </tr>
        </table>

        <!-- Addresses -->
        <table class="address-table">
            <tr>
                <td class="address-box">
                    <div class="address-title">الفاتورة إلى:</div>
                    <p>{{ $order->billing_address['name'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['email'] ?? 'N/A' }}</p>
                    <p dir="ltr">{{ $order->billing_address['phone_number'] ?? ($order->user->Number ?? 'N/A') }}</p>
                </td>
                <td width="4%"></td> <!-- Spacer -->
                <td class="address-box">
                    <div class="address-title">الشحن إلى:</div>
                    <p>{{ $order->billing_address['street'] ?? 'N/A' }}</p>
                    <p>{{ $order->billing_address['city_ar'] ?? 'N/A' }}, {{ $order->billing_address['state_ar'] ?? 'N/A' }}</p>
                    <p>سلطنة عمان</p>
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-right">المنتج</th>
                    <th>الكمية</th>
                    <th>الحجم/الوزن</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_details as $od)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-right">{{ $od->product->fr_Product_Name ?? $od->Product_Name }}</td>
                        <td>{{ $od->Quantity }}</td>
                        <td>
                            @if ($order->order_source == 'whatsapp')
                                {{ $od->Size }}
                            @else
                                @php $size = json_decode($od->Size); @endphp
                                {{ is_null($size?->weight) ? 'N/A' : $size?->weight . ' جرام' }}
                            @endif
                        </td>
                        <td>{{ number_format($od->Price, 3) }}</td>
                        <td>{{ number_format($od->Total_Price, 3) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-left">الإجمالي الفرعي:</td>
                    <td>{{ number_format($order->Sub_Total, 3) }} OMR</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-left">مصاريف الشحن:</td>
                    <td>{{ number_format($order->Delivery_Charge, 3) }} OMR</td>
                </tr>
                @if ($order->Coupon_Amount > 0)
                    <tr>
                        <td colspan="5" class="text-left">الخصم:</td>
                        <td>{{ number_format($order->Coupon_Amount, 3) }} OMR</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="5" class="text-left">المجموع النهائي:</td>
                    <td>{{ number_format($order->Grand_Total, 3) }} OMR</td>
                </tr>
            </tfoot>
        </table>

        <!-- QR Code -->
        <div class="footer">
            @php
                $billingAddress = $order->billing_address;
                $phoneNumber = is_array($billingAddress) ? ($billingAddress['phone_number'] ?? 'N/A') : ($order->user->Number ?? 'N/A');
                $qrData = "Order:" . $order->id . " | Phone:" . $phoneNumber;
            @endphp
            <div style="margin-bottom: 5px;">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(80)->generate($qrData)) }}" />
            </div>
            <p>{{ $order->Order_Number }}</p>
        </div>
    </div>
</body>
</html>
