<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class PrinterOrderController extends Controller
{
    /**
     * Login for the printer app using a simple password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $storedPassword = Setting::where('slug', 'printer_app_password')->first();

        if (!$storedPassword || $request->password !== $storedPassword->value) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid password')
            ], 401);
        }

        // Use a dedicated user for the printer app
        $user = User::firstOrCreate(
            ['email' => 'printer@app.local'],
            [
                'name' => 'Printer App',
                'password' => Hash::make(\Illuminate\Support\Str::random(20)),
                'is_active' => true,
            ]
        );

        $token = $user->createToken('PrinterAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => __('Login successful'),
            'token' => $token
        ]);
    }
    /**
     * Get a paginated list of all orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $orders = Order::with(['order_details', 'user', 'deliveryMan'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $orders->getCollection()->transform(function ($order) {
            $order->print_url = route('printer.order.print', ['id' => $order->id]);
            $order->billing_address_formatted = $order->formatted_billing_address;
            $order->shipping_address_formatted = $order->formatted_shipping_address;
            $order->billing_address_json = $order->billing_address_details;
            $order->shipping_address_json = $order->shipping_address_details;
            return $order;
        });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Update the printed status of an order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrintedStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'is_printed' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $order = Order::find($request->order_id);
        $order->is_printed = $request->is_printed;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => __('Order printed status updated successfully'),
            'data' => $order
        ]);
    }

    /**
     * Generate the printable invoice PDF.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $order = Order::with(['order_details', 'order_details.product', 'user'])->findOrFail($id);

        $pdf = PDF::loadView('orders.printableInvoice', compact('order'), [], [
            'title' => 'Order #' . $order->id,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoArabic' => true,
            'format' => [80, 280]
        ]);

        return $pdf->stream('Invoice-' . $order->Order_Number . '.pdf');
    }
}
