<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        td {
            border-color: black;
        }

        th {
            background: #EEE;
            border-color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-width: 1px;
            padding: 2px 4px;
            position: relative;
            text-align: left;
            font-size: 10px;
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
            font: 12px/1.2 'Open Sans', sans-serif;
            overflow: auto;
            margin: 0;
            padding: 0;
        }

        body {
            box-sizing: border-box;
            overflow: hidden;
            margin: 0 !important;
            padding: 5px 2mm 5px 2mm !important; /* Reduced padding to maximize printable area */
            max-width: 100%;
            width: 80mm;
            direction: ltr;
            text-align: left;
        }

        .item-name {
            font-weight: bold;
            font-size: 10px;
            display: block;
            text-align: left;
            word-wrap: break-word;
        }

        .header-info p {
            margin: 1px 0;
            font-size: 10px;
        }

        .address-info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .total-row {
            font-size: 14px;
            font-weight: bold;
        }

        .nowrap {
            white-space: nowrap;
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
            C.R No.: 1275107
        </p>
        <p>
            C.R Name: Al Akeed Lil Injaz
        </p>
    </div>
    <article class="address-info">
        <address style="text-align:center;">
            @php
                $billing = $order->billing_address;
                $phone = $billing['phone_number'] ?? ($order->user->Number ?? '');
                $addressStr = implode(', ', array_filter([
                    $billing['street'] ?? null,
                    $billing['city_en'] ?? null,
                    $billing['state_en'] ?? null
                ]));
                if (empty($addressStr)) {
                    $addressStr = 'Pick up from branch';
                }
            @endphp
            <p><strong>Name:</strong> {{ $billing['name'] ?? ($order->user->name ?? '') }}</p>
            <p><strong>Mobile:</strong> {{ $phone }}</p>
            <p><strong>Address:</strong> {{ $addressStr }}</p>
        </address>
        <table class="meta" style="font-size: 70%; border-collapse: collapse; direction: ltr; width: 100%;">
            <tr>
                <th style="text-align: left;"><span>Order No.</span></th>
                <td style="text-align: right;" class="nowrap"><span>{{ $order->Order_Number }}</span></td>
            </tr>
            <tr>
                <th style="text-align: left;"><span>Total Price</span></th>
                <td style="text-align: right;" class="nowrap"><span>{{ number_format($order->Grand_Total, 3) }}</span></td>
            </tr>
        </table>
        <br>
        <table style="font-size: 75%; border-collapse: collapse; direction: ltr;" autosize="1" width="100%">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;"><span>Item</span></th>
                    <th style="width: 20%; text-align: center;"><span>Price</span></th>
                    <th style="width: 15%; text-align: center;"><span>Qty</span></th>
                    <th style="width: 20%; text-align: center;"><span>Total</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_details as $item)
                    <tr>
                        <td style="text-align: left; vertical-align: top;">
                            <span class="item-name">{{ $item->product->en_Product_Name ?? $item->Product_Name }}</span>
                        </td>
                        <td style="text-align: center; vertical-align: top;" class="nowrap"><span>{{ number_format($item->Price, 3) }}</span></td>
                        <td style="text-align: center; vertical-align: top;" class="nowrap"><span>{{ (int) $item->Quantity }}<span></td>
                        <td style="text-align: center; vertical-align: top;" class="nowrap"><span>{{ number_format($item->Total_Price, 3) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="balance"
            style="float: right; margin-top: 10px; font-size: 80%; border-collapse: collapse; direction: ltr; width: 100%;">
            <tr>
                <th style="text-align: left;"><span>Delivery Charge</span></th>
                <td style="text-align: right;" class="nowrap">{{ number_format($order->Delivery_Charge ?? 0, 3) }}</td>
            </tr>
            <tr>
                <th style="text-align: left;"><span>Discount</span></th>
                <td style="text-align: right;" class="nowrap">{{ number_format($order->Coupon_Amount ?? 0, 3) }}</td>
            </tr>
            <tr>
                <th style="text-align: left;"><span>Order Date</span></th>
                <td style="text-align: right;" class="nowrap">{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <th style="text-align: left;"><span>Payment Method</span></th>
                <td style="text-align: right;">
                    {{ $order->Payment_Method == 'COD' ? 'Cash on Delivery' : $order->Payment_Method }}
                </td>
            </tr>
            @if($order->collection_method)
            <tr>
                <th style="text-align: left;"><span>Collection Method</span></th>
                <td style="text-align: right;">
                    {{ ucfirst($order->collection_method) }}
                </td>
            </tr>
            @endif
            <tr class="total-row">
                <th style="text-align: left;"><span>Grand Total</span></th>
                <td style="text-align: right;" class="nowrap">{{ number_format($order->Grand_Total, 3) }}</td>
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
        <p>Order No.: {{ $order->Order_Number }}</p>
    </aside>
</body>

</html>