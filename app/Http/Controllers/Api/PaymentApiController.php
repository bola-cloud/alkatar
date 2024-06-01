<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Http\Services\BasePaymentService;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentApiController extends Controller
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }
    public function paymentNotifier(Request $request, $id)
    {
        $payment_id = $request->input('paymentId', '-1');
        $payer_id = $request->input('PayerID', '-1');
        $im_payment_id = $request->input('payment_id', '-1');
        $this->logger->log('Payment Start', '==========');
        $this->logger->log('Payment paymentId', $payment_id);
        $this->logger->log('Payment PayerID', $payer_id);
        $order = Order::where(['Order_Number' => $id, 'Payment_Status' => PAYMENT_PENDING])->first();
        if (is_null($order)) {
            return redirect()->route('checkout')->with('error', SWR);
        }
        $payment_id = $order->txn;
        $data = ['Order_Number' => $order->Order_Number, 'payment_method' => getPaymentMethodId($order->Payment_Method), 'currency' => 'USD'];
        $getWay = new BasePaymentService($data);
        if ($payer_id != '-1') {
            $payment_data = $getWay->paymentConfirmation($payment_id, $payer_id);
        } else {
            $payment_data = $getWay->paymentConfirmation($payment_id);
        }

        $this->logger->log('Payment done for order', json_encode($order));
        $this->logger->log('Payment details', json_encode($payment_data));

        if ($payment_data['success']) {
            if ($payment_data['data']['payment_status'] == 'success') {
                DB::beginTransaction();
                try {
                    $order->Payment_Status = PAYMENT_SUCCESS;
                    $order->save();
                    $this->logger->log('status', 'paid');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->logger->log('End with Exception', $e->getMessage());
                }
                return redirect()->route('checkout.thankyou_page')->with('success', 'Order Created Successfully!');
            }
        }
        return redirect()->back()->with('error', 'Payment has been declined');
    }

    public function paymentSubscriptionNotifier(Request $request, $id)
    {
        return redirect()->back()->with('error', 'Payment has been declined');
    }

    public function paymentCancel(Request $request)
    {
        return redirect()->back()->with('error', 'Payment has been declined');
    }
}
