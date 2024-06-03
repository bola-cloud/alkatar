<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            "session_id" => "required|string",
            "user_id" => "required|integer",
            "order_number" => "required|string",
            "amount" => "required|numeric",
            "status" => "required|string|in:CREATED,PAYED,REJECTED",
        ]);

        $payment = PaymentModel::create([
            "session_id" => $request->session_id,
            "user_id" => $request->user_id,
            "order_number" => $request->order_number,
            "amount" => $request->amount,
            "status" => $request->status,
        ]);

        return response()->json($payment, 201);
    }
    

    public function getPayment($id)
    {
        $payment = PaymentModel::find($id);

        if (!$payment) {
            return response()->json(["message" => "Payment not found"], 404);
        }

        return response()->json($payment);
    }

    public function updatePayment(Request $request, $id)
    {
        $payment = PaymentModel::find($id);

        if (!$payment) {
            return response()->json(["message" => "Payment not found"], 404);
        }

        $request->validate([
            "session_id" => "required|string",
            "user_id" => "required|integer",
            "amount" => "required|numeric",
            "status" => "required|string|in:CREATED,PAYED,REJECTED",
        ]);

        $payment->session_id = $request->session_id;
        $payment->user_id = $request->user_id;
        $payment->amount = $request->amount;
        $payment->status = $request->status;
        $payment->save();

        return response()->json($payment);
    }

    public function deletePayment($id)
    {
        $payment = PaymentModel::find($id);

        if (!$payment) {
            return response()->json(["message" => "Payment not found"], 404);
        }

        $payment->delete();

        return response()->json(["message" => "Payment deleted"]);
    }
}
