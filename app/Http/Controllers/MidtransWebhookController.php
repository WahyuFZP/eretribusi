<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Bill;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans server-to-server notification (webhook).
     *
     * Midtrans will POST JSON payloads describing transaction status changes.
     */
    public function notification(Request $request)
    {
        Log::info('Received Midtrans notification', ['payload' => $request->all()]);

        try {
            // Use Midtrans SDK helper to parse/validate notification when available
            $notif = new \Midtrans\Notification();
        } catch (\Throwable $e) {
            // Fallback: use raw request payload
            Log::warning('Midtrans Notification class unavailable, using raw payload: '.$e->getMessage());
            $notif = (object) $request->all();
        }

        $transactionStatus = $notif->transaction_status ?? ($request->input('transaction_status'));
        $orderId = $notif->order_id ?? ($request->input('order_id'));
        $fraudStatus = $notif->fraud_status ?? ($request->input('fraud_status'));

        if (! $orderId) {
            Log::warning('Midtrans notification missing order_id', ['payload' => $request->all()]);
            return response()->json(['ok' => false, 'reason' => 'missing order_id'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();
        if (! $payment) {
            Log::warning('Payment not found for Midtrans order_id', ['order_id' => $orderId]);
            // Return 200 so Midtrans won't keep retrying; you may change to 404 if desired
            return response()->json(['ok' => true, 'message' => 'payment_not_found'], 200);
        }

        // Save raw gateway response for auditing
        try {
            $payment->gateway_response = $request->all();
        } catch (\Throwable $e) {
            Log::warning('Failed to set gateway_response: '.$e->getMessage());
        }

        $newStatus = $payment->status;

        switch (strtolower($transactionStatus)) {
            case 'capture':
                // for credit card transactions
                if ($fraudStatus === 'challenge') {
                    $newStatus = 'challenge';
                } else {
                    $newStatus = 'paid';
                }
                break;
            case 'settlement':
            case 'settled':
                $newStatus = 'paid';
                break;
            case 'pending':
                $newStatus = 'pending';
                break;
            case 'deny':
                $newStatus = 'failed';
                break;
            case 'cancel':
                $newStatus = 'cancelled';
                break;
            case 'expire':
            case 'expired':
                $newStatus = 'expired';
                break;
            default:
                $newStatus = $transactionStatus;
                break;
        }

        $payment->status = $newStatus;

        if (in_array($newStatus, ['paid', 'settled'])) {
            $payment->paid_at = now();
        }

        try {
            $payment->save();
        } catch (\Throwable $e) {
            Log::error('Failed to save payment after Midtrans notification: '.$e->getMessage());
            return response()->json(['ok' => false], 500);
        }

        // Update related Bill when payment is successful
        try {
            $bill = $payment->bill;
            if ($bill && $newStatus === 'paid') {
                $bill->status = 'paid';
                // set paid_amount to invoice amount or accumulate
                $bill->paid_amount = $bill->paid_amount + ($payment->amount ?? 0);
                $bill->save();
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to update bill from Midtrans notification: '.$e->getMessage());
        }

        Log::info('Midtrans notification processed', ['order_id' => $orderId, 'status' => $newStatus]);

        return response()->json(['ok' => true], 200);
    }
}
