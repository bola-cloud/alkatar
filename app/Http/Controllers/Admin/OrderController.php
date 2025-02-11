<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Controller;
use App\Jobs\OrderConfirmMail;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetails;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    protected $paymentController;

    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }
    public function orders(Request $request, $status)
    {
        if ($request->ajax()) {
            if ($status == 'pending') {
                $data = Order::where('Order_Status', ORDER_PENDING);
            } elseif ($status == 'processing') {
                $data = Order::where('Order_Status', ORDER_PROCESSING);
            } elseif ($status == 'shipped') {
                $data = Order::where('Order_Status', ORDER_SHIPPED);
            } elseif ($status == 'delivered') {
                $data = Order::where('Order_Status', ORDER_DELIVERED);
            } elseif ($status == 'returned') {
                $data = Order::where('Order_Status', ORDER_RETURN);
            } elseif ($status == 'all') {
                $data = Order::query();
            }
            $data = $data->orderBy('id', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) use ($status) {
                    $btn = '<div class="action__buttons">';
                    // $btn = $btn . '<a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#invoiceModal' . $data->id . '" title="' . __('Invoice') . '"><i class="fas fa-file-invoice"></i></a>';
                    $btn = $btn . '<a href="javascript:void(0)" class="btn-action" onclick="orderDetails(' . $data->id . ')" title="' . __('Invoice') . '"><i class="fas fa-file-invoice"></i></a>';
                    $btn = $btn . '<a href="javascript:void(0)" class="btn-action" onclick="orderStatusEdit(' . $data->id . ')" title="' . __('Change Status') . '"><i class="fas fa-info-circle"></i></a>';

                    if ($data->Order_Status == ORDER_PENDING ) {
                        $btn = $btn . '<a href="' . route('admin.order_send_to_whatsapp', encrypt($data->id)) . '" class="btn-action send-to-whatsapp"><i class="fa-brands fa-whatsapp"></i></a>';
                    }
                    $btn = $btn . '<a href="' . route('admin.order_delete', encrypt($data->id)) . '" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn = $btn . '</div>';
                    return $btn;
                })
                ->addColumn('User', function ($data) {
                    return $data->user != null ? $data->user->name : __('Guest User');
                })
                ->addColumn('phone_number', function ($data) {
                    if (is_null($data->billing_address)) {
                        return 'N/A';
                    }
                    $serialized_billing = json_decode($data->billing_address);

                    if (isset($serialized_billing->phone_number)) {
                        return $serialized_billing->phone_number;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn("State", function ($data) {
                    if (is_null($data->billing)) {
                        return 'N/A';
                    }

                    return $data->billing->state_en;


                    // $order_state = State::where("id", $data->billing->State)->first();
                    // return $order_state['name_en'];
                })
                ->addColumn("City", function ($data) {
                    if (is_null($data->billing_address)) {
                        return 'N/A';
                    }


                    $serialized_billing = json_decode($data->billing_address);
                    if (isset($serialized_billing->city_ar)) {
                        return $serialized_billing->city_ar;
                    } else {
                        return 'N/A';
                    }
                    // $order_city = City::where('id', $data->billing->City)->first();
                    // return $order_city['name_en'];
                })
                ->addColumn('GrandTotal', function ($data) {
                    return $data->Grand_Total . ' OMR';
                })
                // ->addColumn('Products', function ($data) {
                //     $html = '';
                //     foreach ($data->order_details as $or) {
                //         $html .= '<img src="' . asset(IMG_PRODUCT_PATH . $or->product->Primary_Image) . '" border="0" height="50" class="img-rounded mr-1" align="center" />';
                //     }
                //     return $html;
                // })
                ->addColumn("order_date", function ($data) {
                    return date('d-m-Y', strtotime($data->created_at));
                })
                ->addColumn('order_time', function ($data) {
                    return $data->created_at->timezone('Asia/Muscat')->format('h:i A');
                    // return date('h:i A', strtotime($data->created_at));
                })
                ->addColumn('Payment_Method', function ($data) {
                    $payment_method = $data->Payment_Method;

                    if ($payment_method === 'COD') {
                        return '<span style="color:green; font-weight: bold;">' . $payment_method . '</span>';
                    } else {
                        return '<span style="color:blue; font-weight: bold;">' . $payment_method . '</span>';
                    }
                })
                ->addColumn('types', function ($data) {
                    $html = '';
                    foreach ($data->order_details as $key => $or) {
                        if (count($data->order_details) - 1 == $key) {
                            if ($or->product->type == PRODUCT_PHYSICAL) {
                                $html .= 'Physical';
                            } elseif ($or->product->type == PRODUCT_DIGITAL) {
                                $html .= 'Digital';
                            }
                        } else {
                            if ($or->product->type == PRODUCT_PHYSICAL) {
                                $html .= 'Physical,';
                            } elseif ($or->product->type == PRODUCT_DIGITAL) {
                                $html .= 'Digital,';
                            }
                        }
                    }
                    return $html;
                })
                ->addColumn('Coupon', function ($data) {
                    return is_null($data->Coupon_Id) ? 'N/A' : $data->coupon->CouponCode;
                })
                // ->addColumn('digital_goods', function ($data) {
                //     if (validDigitalSend($data->id)) {
                //         return '<a href="' . route('admin.digital_product_send', encrypt($data->id)) . '" class="btn btn-outline-primary small rounded" title="' . __('Send') . '">' . __('Send') . '</a>';
                //     } else {
                //         return 'N/A';
                //     }
                // })
                ->addColumn('order_source', function ($data) {
                    return $data->order_source ?? 'الموقع الكتروني';
                })
                ->addColumn('Status', function ($data) {
                    $html = '';
                    if ($data->Order_Status == ORDER_PENDING) {
                        // $html = __('<span class="status bg-primary-light-varient">Pending</span>');
                        $html = '<span class="status bg-primary-light-varient" style="display: block; width: 126%;">' . __('Pending') . '</span>';
                    } elseif ($data->Order_Status == ORDER_PROCESSING) {
                        $html = '<span class="status bg-secondary-light-varient">' . __('Processing') . '</span>';
                    } elseif ($data->Order_Status == ORDER_SHIPPED) {
                        $html = '<span class="status bg-info-light-varient">' . __('Shipped') . '</span>';
                    } elseif ($data->Order_Status == ORDER_DELIVERED) {
                        $html = '<span class="status bg-success-light-varient">' . __('Delivered') . '</span>';
                    } elseif ($data->Order_Status == ORDER_CANCELLED) {
                        $html = '<span class="status bg-danger-light-varient">' . __('Canceled') . '</span>';
                    } elseif ($data->Order_Status == ORDER_RETURN) {
                        $html = '<span class="status bg-danger-light-varient">' . __('Returned') . '</span>';
                    } elseif ($data->Order_Status == ORDER_NOT_PAYMENT_YET) {
                        $html = '<span class="status bg-warning-light-varient">' . __('Not Payment Yet') . '</span>';
                    } elseif ($data->Order_Status == ORDER_DELIVERED_FAILED) {
                        $html = '<span class="status bg-danger-light-varient">' . __('Delivery Failed') . '</span>';
                    }
                    return $html;
                })
                ->rawColumns(['action', 'Status', 'types', 'Payment_Method'])
                ->make(true);
        }
        $data['title'] = __('Order List');
        $data['status_prefix'] = $status;
        return view('admin.pages.orders.list', $data);
    }

    public function order_details(Request $request)
    {
        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($request->id);

        $order['billing_address'] = json_decode($order->billing_address, true);

        return view('admin.pages.orders.details', compact('order'));
    }

    public function order_print(Request $request)
    {
        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($request->id);

        $order['billing_address'] = json_decode($order->billing_address, true);

        return view('admin.pages.orders.invoice', compact('order'));
    }

    public function orderStatusEdit(Request $request)
    {
        $order = Order::query()
            ->find($request->id);
        return view('admin.pages.orders.status', compact('order'));
    }


    public function orderStatusChange(Request $request, $id)
    {
        $id = decrypt($id);
        if (is_null($request->Order_Status)) {
            return redirect()->back()->with('error', __('Status is required!'));
        }
        $order = Order::whereId($id)->first();
        if (!empty($order)) {
            $update = $order->update([
                'Order_Status' => $request->Order_Status,
            ]);

            if (!empty($update)) {
                // if ($order->order_source === 'whatsapp') {
                $url = "https://alsharashoping.com";
                if ($request->Order_Status == ORDER_DELIVERED)
                    $url = route('user.profile.track.my.order', ['id' => encrypt($order->id)]);

                $response = Http::asJson()->post('https://whatsapi.alsharashoping.com/api/v1/whatsapp/change_status', [
                    'phone_number' => $order->user->Number ?? '',
                    'name' => $order->user->name ?? '',
                    'booking_id' => $order->Order_Number,
                    'status' => $order->getStatusLang()[$request->Order_Status],
                    'url' => $url,
                ]);
                if ($response->failed()) {
                    dd($response);
                    return response()->json(['error' => $response], 500);
                }
                // }

                $this->statusChangeEmail($order, $request->Order_Status);
                return redirect()->back()->with('success', __('Status successfully changed!'));
            }
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
        return redirect()->back()->with('error', __('Order not found!'));
    }


    public function bulkStatusUpdate(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        $newStatus = $request->input('bulk_status');

        if (empty($orderIds) || empty($newStatus)) {
            return redirect()->back()->with('error', __('Please select orders and a status.'));
        }

        $updated = Order::whereIn('id', $orderIds)->update(['Order_Status' => $newStatus]);

        if ($updated) {
            // Send status change emails
            $orders = Order::whereIn('id', $orderIds)->get();
            // foreach ($orders as $order) {
            //     $this->statusChangeEmail($order, $newStatus);
            // }

            return redirect()->back()->with('success', __('Orders status updated successfully.'));
        }

        return redirect()->back()->with('error', __('Failed to update orders status.'));
    }

    public function statusChangeEmail($order, $order_status)
    {
        $ship = json_decode($order->shipping_address, true);
        $data['userName'] = $ship['name'] ?? null;
        $data['userEmail'] = $ship['email'] ?? null;
        $data['order'] = $order;
        $data['companyName'] = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $data['subject'] = __('Shipment Process');
        $data['data'] = $order_status;
        $data['template'] = 'email.order-status-change';
        dispatch(new OrderConfirmMail($data))->onQueue('email-send');
    }

    public function orderDelete($id)
    {
        $id = decrypt($id);
        $delete = Order::whereId($id)->delete();
        if (!empty($delete)) {
            OrderDetails::where('Order_Id', $id)->delete();
            return redirect()->back()->with('success', __('Successfully Deleted!'));
        }
        return redirect()->back()->with('error', __('Something went wrong!'));
    }

    public function orderSendToWhatsapp($id)
    {
        $id = decrypt($id);
        $order = Order::whereId($id)->with('order_details.product')->first();

        $checkoutProduct = [];

        foreach ($order->order_details as $item) {
            $checkoutProduct[] = [
                'name' => Str::limit($item->product->en_Product_Name, 35),
                'quantity' => (int)$item->Quantity,
                'unit_amount' => number_format($item->Price, 3) * 1000,
            ];
        }

        if ($order->Delivery_Charge != 0) {
            $checkoutProduct[] = [
                'name' => 'Shipping Charge',
                'quantity' => 1,
                'unit_amount' => number_format($order->Delivery_Charge, 3) * 1000,
            ];
        }


        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'thawani-api-key' => env('THAWANI_TEST_SECRET_KEY'),
        ])->post(env('THAWANI_TEST_CHECKOUT_URL') . '/checkout/session', [
            'client_reference_id' => $order->Order_Number,
            'mode' => 'payment',
            'products' => $checkoutProduct,
            'success_url' => route('thawani.success', [
                'order_number' => $order->Order_Number,
            ]),
            'cancel_url' => route('thawani.cancel', [
                'order_number' => $order->Order_Number,
            ]),
            'metadata' => [
                'order_number' => $order->Order_Number,
                'shipping_charge' =>  (float)$order->Delivery_Charge,
                'subtotal' =>  $order->Sub_Total,
                'discount' =>  (float)$order->Coupon_Amount,
                'grand_total' =>  (float)$order->Grand_Total,
                'tax' =>  (float)$order->Tax,
            ]
        ]);

        if ($response->successful()) {
            $paymentJsonData = $response->json();
            // create new request body for create payment
            $payment = [
                'session_id' => $paymentJsonData['data']['session_id'],
                'user_id' => $order->User_Id,
                'order_number' => $order->Order_Number,
                'amount' => $order->Grand_Total,
                'status' => 'CREATED',
            ];


            $paymentRequest = new Request($payment);


            // create payment
            $this->paymentController->createPayment($paymentRequest);

            $paymentUrl = env('THAWANI_TEST_PAY_URL') . $paymentJsonData['data']['session_id'] . '?key=' . env("THAWANI_TEST_PUBLIC_KEY");

            $serialized_billing = json_decode($order->billing_address);

            $phoneNumber = null;
            if (isset($serialized_billing->phone_number)) {
                $phoneNumber = $serialized_billing->phone_number;
            }

            $pdfUrl = route('order.print', ['id' => $order->id]);
            $response = Http::asForm()->post('https://whatsapi.alsharashoping.com/api/v1/whatsapp/payment_pdf', [
                'phone_number' => $phoneNumber,
                'payment_url' => $paymentUrl,
                'pdf' => $pdfUrl,
                'price' => $order->Grand_Total,
                'language' => session('APP_LOCALE') == 'fr' ? 'ar' : 'en'
            ]);



            if ($response->successful()) {

                return redirect()->back()->with('success', __('Successfully Send To Whatsapp!'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong!'));
            }
        }
        return redirect()->back()->with('error', __('Something went wrong!'));
    }


    //orderSendToWhatsapp

    public function transactionsList(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('Payment_Method', '!=', COD);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_email', function ($data) {
                    $email = json_decode($data->billing_address, true);
                    return $email['email'];
                })
                ->addColumn('GrandTotal', function ($data) {
                    return  $data->Grand_Total . ' OMR';
                })
                ->addColumn('status', function ($data) {
                    $html = '';
                    if ($data->Order_Status == ORDER_PENDING) {
                        $html = __('<span class="status bg-primary-light-varient">Pending</span>');
                    } elseif ($data->Order_Status == ORDER_PROCESSING) {
                        $html = __('<span class="status bg-secondary-light-varient">Processing</span>');
                    } elseif ($data->Order_Status == ORDER_SHIPPED) {
                        $html = __('<span class="status bg-info-light-varient">Shipped</span>');
                    } elseif ($data->Order_Status == ORDER_DELIVERED) {
                        $html = __('<span class="status bg-success-light-varient">Delivered</span>');
                    } elseif ($data->Order_Status == ORDER_CANCELLED) {
                        $html = __('<span class="status bg-danger-light-varient">Canceled</span>');
                    } elseif ($data->Order_Status == ORDER_RETURN) {
                        $html = __('<span class="status bg-danger-light-varient">Returned</span>');
                    } elseif ($data->Order_Status == ORDER_NOT_PAYMENT_YET) {
                        $html = __('<span class="status bg-warning-light-varient">Not Payment Yet</span>');
                    } elseif ($data->Order_Status == ORDER_DELIVERED_FAILED) {
                        $html = __('<span class="status bg-danger-light-varient">Delivery Failed</span>');
                    }
                    return $html;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = __('All Transactions');
        return view('admin.pages.transactions.list', $data);
    }

    public function digitalProductSend($id)
    {
        $id = decrypt($id);
        $order = Order::whereId($id)->with('order_details', 'order_details.product')->first();
        if (!is_null($order)) {
            $data['title'] = __('Digital Product Send');
            $data['order'] = $order;
            return view('admin.pages.orders.digital-send', $data);
        }
        return redirect()->back()->with('error', __('No order found'));
    }

    public function digitalProductMail(Request $request)
    {
        $data['userName'] = 'John Doe';
        $data['userEmail'] = $request->mail_address;
        $data['data'] = $request->link;
        $data['subject'] = __('Digital Product Send');
        $data['template'] = 'email.digital-product-send';
        dispatch(new OrderConfirmMail($data))->onQueue('email-send');
        return redirect()->back()->with('success', __('Mail successfully send!'));
    }
}
