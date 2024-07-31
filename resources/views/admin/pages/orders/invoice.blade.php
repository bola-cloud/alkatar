<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        
        .invoice-modal {
            direction: rtl;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .invoice-modal * {
            box-sizing: border-box;
        }
        .invoice-container {
        
            margin: 0 auto;
            padding: 20px;
        }
   
        .invoice-title {
            margin: 0;
            font-size: 24px;
        }
        .invoice-logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .invoice-logo {
            max-width: 100px;
        }
        .invoice-logo-text {
            font-size: 20px;
            font-weight: bold;
        }
        .order-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-top: 2px solid #ddd;
            padding-top: 20px;
        }

        .order-details p {
            color: #000;
        }

        .company-details, .order-info {
            width: 48%;
        }
        .address-container {
            display: flex;
            margin-bottom: 20px;
        }
        .billing-address, .shipping-address {
            width: 50%;
        }
        .address-box {
            border: 1px solid #ddd;
            padding: 10px;
            height: 100%;
        }
        .address-title {
            padding: 15px 5px;
            border-bottom: 1px solid #ddd;
            margin: -10px -10px 10px -10px;
            font-weight: bold;
        }
        .invoice-modal table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-modal th, .invoice-modal td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        .invoice-modal th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
        .invoice-footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
         .invoice-modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
         }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                zoom: 80%;
            }
            .invoice-header {
                background-color: #28a745 !important;
                color: white !important;
            }
        }
    </style>
</head>
<body >
    <div class="invoice-modal">
        <div class="invoice-container">
          
            <div class="invoice-logo-container">
                <img src="{{ asset(IMG_LOGO_PATH . allsetting()['main_logo']) }}" alt="Logo" class="invoice-logo">
                <span class="invoice-logo-text">الفاتورة</span>
            </div>

            <div class="order-details">
                <div class="company-details">
                    <p>شركة مطاحن و تمور الشرع</p>
                    <p>الهاتف: +96893904070</p>
                    <p>alsaraamills@gmail.com</p>
                    <p>https://alsharashopping.com</p>
                </div>
                <div class="order-info">
                    <p>تاريخ الإضافة: {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                    <p>وقت الإضافة: {{ date('h:i A', strtotime($order->created_at)) }}</p>
                    <p>رقم الطلب: {{ $order->Order_Number }}</p>
                    <p>طريقة الدفع: {{ $order->Payment_Method }}</p>
                    <p>طريقة الشحن: مصاريف الشحن</p>
                </div>
            </div>

            <div class="address-container">
                <div class="billing-address">
                    <div class="address-box">
                        <div class="address-title">الفاتورة الى</div>
                        <p>اسم العميل: {{ $bill['name'] ?? 'N/A' }}</p>
                        <p>البريد الاكتروني: {{ $bill['email'] ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="shipping-address">
                    <div class="address-box">
                        <div class="address-title">الشحن الى</div>
                        <p>العنوان: {{ $ship['street'] ?? 'N/A' }}</p>
                        <p>المحافظة: {{ $ship['state_ar'] ?? 'N/A' }}</p>
                        <p>المدينة: {{ $ship['city_ar'] ?? 'N/A' }}</p>
                        <p>الدولة: سلطنة عمان</p>
                    </div>
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
    </div>
</body>

<script>
        print()
    </script>
</html>