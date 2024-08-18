<?php
//
//namespace App\Http\Controllers\Api;
//
//use App\Http\Requests\StoreOrderRequest;
//use App\Models\Order;
//use App\Models\Service;
//use App\Traits\MuscatAppsService;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Str;
//
//class ThawaniPayController extends Controller
//{
//    use MuscatAppsService;
//
//    public function checkout(StoreOrderRequest $request)
//    {
//        $validated = $request->validated();
//        Log::info('Checkout requested', ['request' => $validated]);
//        $service = Service::find($validated['service_id']);
//        $order = Order::create([
//            'Id' => Str::uuid()->toString(),
//            "Title" => $service->Title,
//            "service_id" => $service->id,
//            "amount" => $validated['amount'],
//            'order_status' => 'unpaid',
//            'category' => 'direct',
//            'paymentkeyid' => $service->categoryRelation?->paymentKey->id ?? 0,
//            'phonesender' => $validated['phone_sender'] ?? '',
//            'namesender' => $validated['name_sender'] ?? '',
//            'phonereceiver' => $validated['phone_receiver'] ?? '',
//            'namereceiver' => $validated['name_receiver'] ?? '',
//            'gift_type_id' => $validated['gift_type'] ?? null,
//            'gift_cat_id' => $validated['gift_cat'] ?? null,
//            'creation_date' => now(),
//            'paid_from' => $validated['paid_from'],
//        ]);
//
//        Log::info('Order created', ['order_id' => $order->Id]);
//        $data = [
//            'client_reference_id' => time(),
//            'mode' => 'payment',
//            'products' => [
//                [
//                    'name' => $service->Title,
//                    'quantity' => 1,
//                    'unit_amount' => round($validated['amount'] * 1000, 2)
//                ]
//            ],
//            'success_url' => route('success', ['order_id' => $order->Id, 'form_type' => $request->get('form_type')]),
//            'cancel_url' => route('fail', ['order_id' => $order->Id, 'form_type' => $request->get('form_type')]),
//            "metadata" => [
//                "service name" => $service->Title,
//                "paid_from" => $validated['paid_from'],
//                "order_id" => $order->Id,
//            ]
//        ];
//        $paymentKey = $order->service?->categoryRelation?->PaymentKey;
//        $secretKey = $paymentKey?->Secret_Key;
//        $publicKey = $paymentKey?->Public_Key;
//        $response = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'thawani-api-key' => $secretKey
//        ])->post(config('muscatapps.thawani_api_url') . '/api/v1/checkout/session', $data);
//        Log::info('Thawani API session response', ['response' => $response->body()]);
//        $sessionId = $response['data']['session_id'] ?? '';
//        Session::put('pay_session_id', $sessionId);
//        $order->update(['session_id' => $sessionId]);
//        $to = config('muscatapps.thawani_api_url') . '/pay/' . $sessionId . "?key={$publicKey}";
//        Log::info('Redirecting to payment page', ['url' => $to]);
//
//        return response()->json(['url' => $to]);
////        return redirect()->to($to);
//    }
//
//    public function success(Request $request)
//    {
//        $orderId = $request->get('order_id');
//        Log::info('locak at request ', ['requesst' => $request->all()]);
//        Log::info('Payment order id accessed', ['order_id' => $orderId]);
//        $order = Order::FindOrFail($orderId);
//        Log::info('Order status updated on success', ['order_id' => $order->Id]);
//
//        $paymentKey = $order->service?->categoryRelation?->PaymentKey;
//        $secretKey = $paymentKey?->Secret_Key;
//        $response = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'thawani-api-key' => $secretKey
//        ])->get(config('muscatapps.thawani_api_url') . "/api/v1/checkout/session/{$order->session_id}");
//
//        Log::info('Payment response received', ['response' => $response->json()]);
//
//        if (isset($response['success'])) {
//            $order->update(['order_status' => $response['data']['payment_status'] ?? $order->order_status]);
//            if (isset($order->phonesender)) {
//                $message = "شكراً لك على تبرعك الكريم، بفضل دعمك، \n يمكننا مواصلة جهودنا لتحسين حياة المحتاجين. \n نقدر لك سخاءك.";
//                $this->sendSms($order->phonesender, $message);
//                Log::info('SMS sent to sender', ['phone' => $order->phonesender, 'message' => $message]);
//
//            }
//
//            if (isset($order->phonereceiver)) {
//                $programTitle = $request->input('form_type') == 'gift' ? $order->giftCategory->title : $order->service->Title;
//                Log::info('locak at programTitle ', ['programTitle' => $programTitle]);
//                $message = isset($order->namesender)
//                    ? "{$order->namesender} أهداك أجرا بتبرعه عنك لبرنامج {$programTitle}"
//                    : " تم اهدئك أجرا بالتبرع عنك لبرنامج {$programTitle}";
//                $this->sendSms($order->phonereceiver, $message);
//                Log::info('SMS sent to receiver', ['phone' => $order->phonereceiver, 'message' => $message]);
//
//            }
//
//            //phone_receiver
//
//        }
//        return redirect()->to("/#/donations/paymentstatus/?payId={$order->Id}");
////        return redirect()->to($request->getHost() . "/services/paymentstatus/?payId={$order->Id}");
//    }
//
//    public function fail(Request $request)
//    {
//        $orderId = $request->get('order_id');
//        Log::info('Payment order id accessed', ['order_id' => $orderId]);
//        $order = Order::FindOrFail($orderId);
//        Log::info('Payment failed', ['order_id' => $orderId]);
//        $paymentKey = $order->service?->categoryRelation?->PaymentKey;
//        $secretKey = $paymentKey?->Secret_Key;
//        $response = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'thawani-api-key' => $secretKey
//        ])->get(config('muscatapps.thawani_api_url') . "/api/v1/checkout/session/{$order->session_id}");
//        if (isset($response['success'])) {
//            $order->update(['order_status' => 'cancel']);
//        }
//        Log::info('Order status updated on failure', ['order_id' => $order->Id]);
//        return redirect()->to("/#/donations/paymentstatus/?payId={$order->Id}");
////        return redirect()->to($request->getHost()."/services/paymentstatus/?payId={$order->Id}");
//
////        return redirect()->to('https://zakat-website.netlify.app/aboutus/');
//
//    }
//
////    public function refund()
////    {
////        $sessionId = Session::get('pay_session_id');
////        $response = Http::withHeaders([
////            'Content-Type' => 'application/json',
////            'thawani-api-key' => 'Pz8qRhENkL9i3jtPYnpdGq1hXxSfUm'
////        ])->get(config('muscatapps.thawani_api_url') ."/api/v1/checkout/session/{$sessionId}");
////
////        $paymentObject = Http::withHeaders([
////            'Content-Type' => 'application/json',
////            'thawani-api-key' => 'Pz8qRhENkL9i3jtPYnpdGq1hXxSfUm'
////        ])->get(config('muscatapps.thawani_api_url') ."/api/v1/payments?checkout_invoice={$response['data']['invoice']}");
////
////        $card = current($paymentObject['data']);
////
////        $refund = Http::withHeaders([
////            'Content-Type' => 'application/json',
////            'thawani-api-key' => 'Pz8qRhENkL9i3jtPYnpdGq1hXxSfUm'
////        ])->post(config('muscatapps.thawani_api_url') .'/api/v1/refunds', [
////            'payment_id' => $card['payment_id'],
////            'reason' => 'refund'
////        ]);
////
////        $refundStatus = Http::withHeaders([
////            'Content-Type' => 'application/json',
////            'thawani-api-key' => 'Pz8qRhENkL9i3jtPYnpdGq1hXxSfUm'
////        ])->get(config('muscatapps.thawani_api_url') ."/api/v1/refunds/{$refund['data']['refund_id']}");
////
////    }
//
//}
