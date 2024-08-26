<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
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
                <p><strong>شركة مطاحن و تمور الشرع</strong></p>
                <p>الهاتف: +96893904070</p>
                <p>alsaraamills@gmail.com</p>
                <p>https://alsharashopping.com</p>
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
            <div class="address-box">
                <div class="address-title">الفاتورة الى</div>
                <p>اسم العميل: {{ $order->billing_address['name'] ?? 'N/A' }}</p>
                <p>البريد الاكتروني: {{ $order->billing_address['email'] ?? 'N/A' }}</p>
                @if ($order->user)
                    <p>رقم الهاتف: {{ $order->user->code . $order->user->Number ?? 'N/A' }}</p>
                @endif

            </div>
            <div class="address-box">
                <div class="address-title">الشحن الى</div>
                <p>العنوان: {{ $order->billing_address['street'] ?? 'N/A' }}</p>
                <p>المحافظة: {{ $order->billing_address['state_ar'] ?? 'N/A' }}</p>
                <p>المدينة: {{ $order->billing_address['city_ar'] ?? 'N/A' }}</p>
                <p>الدولة: سلطنة عمان</p>
                <p>رقم الهاتف: {{ $order->billing_address['phone_number'] ?? 'N/A' }}</p>

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
                        <td>{{ is_null($od->Size) ? 'N/A' : $od->Size }}</td>
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
                <tr class="total-row">
                    <td colspan="5">المجموع النهائي:</td>
                    <td>{{ $order->Grand_Total }} OMR</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p><strong>الملاحظات:</strong></p>

        </div>
    </div>
</body>

<script>
    window.onload = function () {
        window.print();
    }
</script>

</html>