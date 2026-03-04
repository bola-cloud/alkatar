<!DOCTYPE html>
<html lang="ar" dir="rtl">

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
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
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
            <span class="invoice-title">الفاتورة</span>
        </div>

        <div class="invoice-details">
            <div>
                <p><strong>{{ allsetting()['app_title'] ?? 'هاي سبيد' }}</strong></p>
                <p>{{ __('Phone') }}: {{ allsetting()['call_us'] ?? '+968 94974726' }}</p>
                <p>{{ allsetting()['email'] ?? 'alsaraamills@gmail.com' }}</p>
                <p>{{ url('/') }}</p>
            </div>
            <div>
                <p>تاريخ الشراء: {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                <p>وقت الشراء: {{ $order->created_at->timezone('Asia/Muscat')->format('h:i A') }}</p>
                <p>رقم الطلب: {{ $order->Order_Number }}</p>
                <p>طريقة الدفع: {{ $order->Payment_Method }}</p>
                <p>طريقة الشحن: مصاريف الشحن</p>
            </div>
        </div>

        <div class="address-container">

            @php
                $phone = $order->user->Number ?? null;

                if ($phone && preg_match('/^\+?(\d{3})(\d+)/', $phone, $matches)) {
                    $formattedUserPhone = "({$matches[1]}) {$matches[2]}";
                } else {
                    $formattedUserPhone = 'N/A';
                }
            @endphp
            <div class="address-box">
                <div class="address-title">الفاتورة الى</div>
                <p>اسم العميل: {{ $order->billing_address['name'] ?? 'N/A' }}</p>
                <p>البريد الاكتروني: {{ $order->billing_address['email'] ?? 'N/A' }}</p>
                @if ($order->user)
                    <p>رقم الهاتف:
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
                    $formattedPhone = 'N/A';
                }
            @endphp
            <div class="address-box">
                <div class="address-title">الشحن الى</div>
                <p>العنوان: {{ $order->billing_address['street'] ?? 'N/A' }}</p>
                <p>المحافظة: {{ $order->billing_address['state_ar'] ?? 'N/A' }}</p>
                <p>المدينة: {{ $order->billing_address['city_ar'] ?? 'N/A' }}</p>
                <p>الدولة: سلطنة عمان</p>
                <p>رقم الهاتف:
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
                        <td>{{ $od->Quantity }}</td>
                        @if ($order->order_source == 'whatsapp')
                            <td>{{ $od->Size }}</td>
                        @else
                            @php
                                $size = json_decode($od->Size); 
                            @endphp
                            <td>{{ is_null($size?->weight) ? 'N/A' : $size?->weight }} جرام</td>
                        @endif
                        <td>{{ $od->Price }}</td>
                        <td>{{ $od->Total_Price }} OMR</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5">الإجمالي قبل النهائي</td>
                    <td>{{ $order->Sub_Total }} OMR</td>
                </tr>
                <tr>
                    <td colspan="5">مصاريف الشحن</td>
                    <td>{{ $order->Delivery_Charge }} OMR</td>
                </tr>
                @if ($order->Coupon_Amount > 0)
                    <tr>
                        <td colspan="5">الخصم</td>
                        <td>{{ $order->Coupon_Amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="5">الإجمالي بعد الخصم</td>
                        <td>{{ $order->Sub_Total - $order->Coupon_Amount}} OMR</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="5">المجموع النهائي:</td>
                    <td>{{ $order->Sub_Total - $order->Coupon_Amount + $order->Delivery_Charge }} OMR</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p><strong>الملاحظات:</strong></p>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            @php
                $billingAddress = $order->billing_address;
                $phoneNumber = is_array($billingAddress) ? ($billingAddress['phone_number'] ?? 'N/A') : ($order->user->Number ?? 'N/A');
                $qrData = "Order:" . $order->Order_Number . " | Phone:" . $phoneNumber;

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