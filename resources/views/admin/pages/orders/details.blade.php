<style>
    /* Custom invoice styles */
    .invoice-modal-header {
        background-color: #28a745;
        color: white;
    }

    .invoice_parent_container {
        direction: rtl;
        text-align: right;
    }

    .invoice-modal-title {
        color: white;
    }

    .invoice-modal-close {
        color: white;
    }

    .invoice-modal-body {
        font-family: Arial, sans-serif;
        color: #333;

    }

    .invoice_logo_container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .invoice_logo_container p {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .invoice-logo {
        max-width: 100px;
        margin-bottom: 20px;
    }

    .invoice-details,
    .invoice-address,
    .invoice-products {
        margin-bottom: 20px;
    }

    .invoice-details b,
    .invoice-address b {
        display: inline-block;
        min-width: 150px;
    }

    .invoice-address small {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;

    }

    .invoice-products .table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-products th,
    .invoice-products td {
        border: 1px solid #ddd;
        padding: 8px;
    }



    .invoice-footer small {
        color: #000;
        font-size: 16px;
        font-weight: bold;
    }

    .invoice-modal-footer {
        display: flex;
        justify-content: space-between;
    }

    .order_date_container {
        display: flex;
        justify-content: space-between;
        padding: 0 20px;
        border-top: 2px solid #ddd;
        padding-top: 20px;
    }

    .order_date_container p {
        color: #000;
        font-size: 14px;
        margin: 0;
        text-align: right;
    }

    .address_container {
        display: flex;
        padding: 0 20px;
    }

    .address_container div {
        width: 50%;
    }

    .address_container table {
        width: 100%;
        height: 100%;
    }

    .address_container th {
        font-size: 16px;
        border-color: #f2f2f2;
    }

    .address_container small {
        color: #000;
        font-size: 16px;
        margin: 0;
        text-align: right;
    }
</style>

<div class="invoice_parent_container">
    <div class="modal-header invoice-modal-header">
        <h5 class="modal-title invoice-modal-title" id="viewModalLongTitle">{{ __('Invoice') }}</h5>
        <button type="button" class="close invoice-modal-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body invoice-modal-body" id="printDiv">
        <div class="invoice_logo_container">
            <img src="{{ asset(IMG_LOGO_PATH . allsetting()['main_logo']) }}" alt="{{ __('Logo') }}"
                class="invoice-logo" />
            <p>الفاتورة</p>
        </div>

        <div class="order_date_container">

            <div>
                <p>شركة مطاحن و تمور الشرع</p>
                <p>الهاتف: +96893904070</p>
                <p>alsaraamills@gmail.com</p>
                <p>https://alsharashopping.com</p>
            </div>

            <div>
                <p>{{ __('تاريخ الإضافة:') }} {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                <p>{{ __('وقت الإضافة:') }} {{ date('h:i A', strtotime($order->created_at)) }}</p>
                <p>{{ __('رقم الطلب:') }} {{ $order->Order_Number }}</p>
                <p>{{ __('طريقة الدفع:') }} {{ $order->Payment_Method }}</p>
                <p>{{ __('طريقة الشحن:') }} مصاريف الشحن</p>
            </div>




        </div>
    </div>
    @php
        $bill = json_decode($order->billing_address, true);
    @endphp

    <div class="address_container" style="margin-top: 20px">
        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الفاتورة الى</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <small>
                                اسم العميل: {{ $bill['name'] ?? null }} <br>
                                البريد الاكتروني: {{ $bill['email'] ?? null }} <br>
                                <!-- {{ $bill['street'] ?? null }} <br>
                                {{ $bill['state'] ?? null }} <br>
                                {{ $bill['country'] ?? null . __(',') }} {{ $bill['zipcode'] ?? null }} -->
                            </small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @php
            $ship = json_decode($order->shipping_address, true);
        @endphp
        <div style="margin: 0;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الشحن الى</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <small>
                                العنوان: {{ $ship['street'] ?? null }} <br>
                                المحافظة: {{ $ship['state_ar'] ?? null }} <br>
                                المدينة: {{ $ship['city_ar'] ?? null }} <br>
                                الدولة: سلطنة عمان <br>
                            </small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 30px; padding: 0 20px;">
        <div class="col-lg-12 mb-4 invoice-products">
            <div class="card">

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('الاسم') }}</th>
                                <!-- <th>{{ __('الصورة') }}</th> -->
                                <th>{{ __('الكمية') }}</th>
                                <th>{{ __('الحجم') }}</th>
                                <!-- <th>{{ __('اللون') }}</th> -->
                                <th>{{ __('السعر') }}</th>
                                <th>{{ __('الإجمالي') }}</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->order_details as $od)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $od->product->fr_Product_Name }}</td>
                                    <!-- <td><img src="{{ asset(IMG_PRODUCT_PATH . $od->product->Primary_Image) }}" height="50"
                                                                                                                                                class="img-rounded mr-1" /></td> -->
                                    <td>{{ $od->Quantity }}</td>
                                    <td>{{ is_null($od->Size) ? __('N/A') : $od->Size }}</td>
                                    <!-- <td>{{ is_null($od->Color) ? __('N/A') : $od->Color }}</td> -->
                                    <td>{{ $od->Price }}</td>
                                    <td>{{ $od->Total_Price }} OMR</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold;">الإجمالي قبل النهائي</ttdtyle <td colspan="4">
                                </td>
                                <td colspan="4"></td>

                                <td>{{ $order->Sub_Total }} OMR</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;"> مصاريف الشحن </td>
                                <td colspan="4"></td>
                                <td>{{ $order->Delivery_Charge }} OMR</td>
                            </tr>
                            <!-- <tr>
                                <td>{{ __('Tax') }}</td>
                                <td colspan="4"></td>
                                <td>{{ $order->Tax }}</td>
                            </tr>
                            @if (!is_null($order->Coupon_Id))
                                <tr>
                                    <td>{{ __('Discount (-)') }}</td>
                                    <td colspan="4"></td>
                                    <td>{{ $order->Coupon_Amount }}</td>
                                </tr>
                            @endif -->
                            <tr>
                                <td style="font-weight: bold;">المجموع النهائي:</td>
                                <td colspan="4"></td>
                                <td>{{ $order->Grand_Total }} OMR</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer invoice-footer">
                    <small>الملاحظات</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer invoice-modal-footer">
    <button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <a href="{{ route('admin.order.print', $order->id) }}" class="btn btn-info" target="_blank">{{ __('Print') }}</a>
</div>
</div>