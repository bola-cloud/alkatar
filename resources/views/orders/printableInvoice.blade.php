<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        td {
            border-color: black;
        }

        th {
            background: #EEE;
            border-color: black;
        }

        table {
            width: 100%;
        }

        th,
        td {
            border-width: 1px;
            padding: 0.5em;
            position: relative;
            text-align: left;
        }

        th,
        td {
            border-style: solid;
        }

        th {
            background: #EEE;
            border-color: #BBB;
        }

        td {
            border-color: #DDD;
        }

        /* page */

        html {
            font: 12px/1 'Open Sans', sans-serif;
            overflow: auto;
        }

        body {
            box-sizing: border-box;
            overflow: hidden;
            margin: 0 auto !important;
            max-width: 280px;
            /* Slightly wider for better utilization of 80mm */
        }

        .item-name {
            font-weight: bold;
            font-size: 11px;
            display: block;
            text-align: right;
            direction: rtl;
        }

        .header-info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .address-info p {
            margin: 4px 0;
            font-size: 13px;
        }

        .total-row {
            font-size: 16px;
            font-weight: bold;
        }

        article:after {
            clear: both;
            content: "";
            display: table;
        }

        article h1 {
            clip: rect(0 0 0 0);
            position: absolute;
        }

        mpdf-col-q {
            width: auto !important;
        }
    </style>
</head>

<body>
    <div style="text-align:center;" class="header-info">
        @php
            $settings = allsetting();
            $appName = $settings['app_title'] ?? 'Hi Speed';
            $logoName = $settings['app_logo'] ?? null;
            $logoBase64 = null;

            try {
                if ($logoName) {
                    $path = public_path(GeneralSettingsImage() . $logoName);
                } else {
                    $path = public_path('images/logo.png');
                }

                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {
                // Fallback to no image if file reading fails
            }
        @endphp

        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="logo" style="width: 100px; height: auto; margin-bottom: 10px;">
        @endif
        <p>
            <strong>{{ $appName }}</strong>
        </p>

        <p>
            السجل التجاري: {{ $settings['commercial_registration'] ?? 'غير متوفر' }}
        </p>
    </div>
    <article class="address-info">
        <address style="text-align:center;">
            @php
                $billing = $order->billing_address;
                $phone = $billing['phone_number'] ?? ($order->user->Number ?? '');
                $addressStr = implode(', ', array_filter([
                    $billing['street'] ?? null,
                    $billing['city_ar'] ?? null,
                    $billing['state_ar'] ?? null
                ]));
                if (empty($addressStr)) {
                    $addressStr = 'استلام من الفرع';
                }
            @endphp
            <p><strong>الاسم:</strong> {{ $billing['name'] ?? ($order->user->name ?? '') }}</p>
            <p><strong>الجوال:</strong> {{ $phone }}</p>
            <p><strong>العنوان:</strong> {{ $addressStr }}</p>
        </address>
        <table class="meta" style="font-size: 70%; border-collapse: collapse; direction: rtl; width: 100%;">
            <tr>
                <th style="text-align: right;"><span>رقم الطلب</span></th>
                <td style="text-align: left;"><span>{{$order->Order_Number ?? $order->id}}</span></td>
            </tr>
            <tr>
                <th style="text-align: right;"><span>السعر الكلي</span></th>
                <td style="text-align: left;"><span>{{$order->Grand_Total}}</span></td>
            </tr>
        </table>
        <br>
        <table style="font-size: 75%; border-collapse: collapse; direction: rtl;" autosize="1" width="100%">
            <thead>
                <tr>
                    <th style="text-align: right;"><span>الصنف</span></th>
                    <th style="width: 15%; text-align: center;"><span>السعر</span></th>
                    <th style="width: 15%; text-align: center;"><span>الكمية</span></th>
                    <th style="width: 15%; text-align: center;"><span>اجمالي</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_details as $item)
                    <tr>
                        <td style="text-align: right;"><span class="item-name">{{$item->Product_Name}}</span></td>
                        <td style="text-align: center;"><span>{{ number_format($item->Price, 3) }}</span></td>
                        <td style="text-align: center;"><span>{{ (int) $item->Quantity }}<span></td>
                        <td style="text-align: center;"><span>{{ number_format($item->Total_Price, 3) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="balance"
            style="float: right; margin-top: 15px; font-size: 80%; border-collapse: collapse; direction: rtl; width: 100%;">
            <tr>
                <th style="text-align: right;"><span>سعر التوصيل</span></th>
                <td style="text-align: left;">{{ number_format($order->Delivery_Charge ?? 0, 3) }}</td>
            </tr>
            <tr>
                <th style="text-align: right;"><span>الخصم</span></th>
                <td style="text-align: left;">{{ number_format($order->Coupon_Amount ?? 0, 3) }}</td>
            </tr>
            <tr>
                <th style="text-align: right;"><span>تاريخ الطلب</span></th>
                <td style="text-align: left;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <th style="text-align: right;"><span>نوع الدفع</span></th>
                <td style="text-align: left;">
                    {{ $order->Payment_Method == 'COD' ? 'الدفع عند الاستلام' : $order->Payment_Method }}
                </td>
            </tr>
            <tr class="total-row">
                <th style="text-align: right;"><span>الاجمالي الكلي</span></th>
                <td style="text-align: left;">{{ number_format($order->Grand_Total, 3) }}</td>
            </tr>
        </table>
    </article>
    <br>
    <hr>
    <br><br>
    <aside style="text-align: center">
        @php
            $billingAddress = $order->billing_address;
            $phoneNumber = is_array($billingAddress) ? ($billingAddress['phone_number'] ?? 'N/A') : ($order->user->Number ?? 'N/A');
            $qrData = "Order:" . $order->id . " | Phone:" . $phoneNumber;
            
            $qrCodeString = (string) QrCode::size(120)->generate($qrData);
            $svgStart = strpos($qrCodeString, '<svg');
            $cleanQrCode = ($svgStart !== false) ? substr($qrCodeString, $svgStart) : $qrCodeString;
        @endphp
        {!! $cleanQrCode !!}
        <p>رقم الطلب: {{ $order->Order_Number ?? $order->id }}</p>
    </aside>
</body>

</html>