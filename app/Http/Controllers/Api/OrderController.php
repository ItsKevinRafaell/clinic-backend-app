<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with('patient', 'doctor', 'clinic')->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function store(OrderStoreRequest $request){
        $request->validated();

        $data = $request->all();
        $order = Order::create($data);

        $xenditKey = env('XENDIT_SERVER_KEY', '');
        Configuration::setXenditKey($xenditKey);

        $apiInstance = new InvoiceApi();
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => 'INV-'.$order->id,
            'description' => 'Payment for order '.$order->service,
            'amount' => $order->price,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1,
            'success_redirect_url' => 'flutter/success',
            'failure_redirect_url' => 'flutter/failure',
        ]); // \Xendit\Invoice\CreateInvoiceRequest

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            $payment_url = $result->getInvoiceUrl();
            $order->update([
                'payment_url' => $payment_url,
            ]);
            $order->save();
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ], 201);
    }

    public function handleCallback(Request $request){
        $xenditCallbackToken = env('XENDIT_CALLBACK_TOKEN', '');
        $callbackToken = $request->header('x-callback-token');
        if($xenditCallbackToken !== $callbackToken){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $data = $request->all();
        $externalId = $data['external_id'];
        $order = Order::where('id', explode('-', $externalId)[1])->first();
        $order->update([
            'status' => $data['status'],
        ]);
        $order->save();

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function getOrderByPatient($patient_id){
        $orders = Order::where('patient_id', $patient_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function getOrderByDoctor($doctor_id){
        $orders = Order::where('doctor_id', $doctor_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function getOrderByClinic($clinic_id){
        $orders = Order::where('clinic_id', $clinic_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function getSummary($clinic_id){
        $orders = Order::where('clinic_id', $clinic_id)->with('patient', 'doctor', 'clinic')->orderBy('created_at', 'desc')->get();
        $orderCount = $orders->count();
        $totalIncome = $orders->where('status', 'done')->sum('price');
        $doctorCount = $orders->groupBy('doctor_id')->count();
        $patientCount = $orders->groupBy('patient_id')->count();
        return response()->json([
            'status' => 'success',
            'data' => [
                'order_count' => $orderCount,
                'total_income' => $totalIncome,
                'doctor_count' => $doctorCount,
                'patient_count' => $patientCount,
            ],
        ]);
    }
}
