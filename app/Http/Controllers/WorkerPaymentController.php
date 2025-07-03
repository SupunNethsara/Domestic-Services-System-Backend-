<?php

namespace App\Http\Controllers;

use App\Models\WorkerPayment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class WorkerPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'worker_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $payment = WorkerPayment::create([
            'client_id' => $validated['client_id'],
            'worker_id' => $validated['worker_id'],
            'amount' => $validated['amount'],
            'status' => 'pending'
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = PaymentIntent::create([
            'amount' => intval($validated['amount'] * 100),
            'currency' => 'usd',
            'metadata' => [
                'worker_payment_id' => $payment->id,
                'worker_id' => $validated['worker_id']
            ]
        ]);

        return response()->json([
            'payment' => $payment,
            'client_secret' => $intent->client_secret
        ], 201);
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload);

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $workerPaymentId = $paymentIntent->metadata->worker_payment_id;
            $payment = WorkerPayment::find($workerPaymentId);
            if ($payment) {
                $payment->status = 'paid';
                $payment->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
    public function getWorkerPaymentsAll(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
        ]);

        $payments = WorkerPayment::where('worker_id', $validated['worker_id'])
            ->where('client_id', $validated['client_id'])
            ->get();

        return response()->json(['payments' => $payments]);
    }
}
