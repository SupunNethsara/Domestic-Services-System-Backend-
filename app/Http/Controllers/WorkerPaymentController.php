<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkerPayment;
use Exception;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Webhook;

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
            'currency' => 'lkr', // Changed from usd to lkr
            'metadata' => [
                'worker_payment_id' => $payment->id,
                'worker_id' => $validated['worker_id'],
                'client_id' => $validated['client_id']
            ]
        ]);
        $payment->stripe_payment_id = $intent->id;
        $payment->save();

        return response()->json([
            'payment' => $payment,
            'client_secret' => $intent->client_secret
        ], 201);
    }

    public function checkPaymentStatus($paymentId)
    {
        try {
            $payment = WorkerPayment::findOrFail($paymentId);

            return response()->json([
                'status' => $payment->status,
                'payment_id' => $payment->id, // Return our internal ID
                'stripe_payment_id' => $payment->stripe_payment_id,
                'amount' => $payment->amount,
                'paid_at' => $payment->status === 'paid' ? $payment->updated_at : null
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Payment not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
    public function handleStripeWebhook(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $payment = WorkerPayment::where('stripe_payment_id', $paymentIntent->id)->first();

                if (!$payment && isset($paymentIntent->metadata->worker_payment_id)) {
                    $payment = WorkerPayment::find($paymentIntent->metadata->worker_payment_id);
                }

                if ($payment) {
                    $payment->status = 'paid';
                    $payment->save();

                } else {
                    return response()->json(['error' => 'Payment not found'], 404);
                }
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handleTransferUpdate($transfer)
    {
        $payment = WorkerPayment::where('transfer_id', $transfer->id)->first();

        if ($payment) {
            $payment->transfer_status = $transfer->status;
            $payment->save();
        }
    }
    private function handlePaymentFailure($paymentIntent)
    {
        $workerPaymentId = $paymentIntent->metadata->worker_payment_id;
        $payment = WorkerPayment::find($workerPaymentId);

        if ($payment) {
            $payment->status = 'failed';
            $payment->save();
        }
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
    public function manualVerifyPayment($paymentId)
    {
        try {
            $payment = WorkerPayment::findOrFail($paymentId);

            if ($payment->status === 'pending' && $payment->stripe_payment_id) {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_id);

                if ($paymentIntent->status === 'succeeded') {
                    $payment->status = 'paid';
                    $payment->save();


                    return response()->json([
                        'status' => 'paid',
                        'message' => 'Payment manually verified'
                    ]);
                }
            }

            return response()->json([
                'status' => $payment->status,
                'message' => 'No update needed'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Verification failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
