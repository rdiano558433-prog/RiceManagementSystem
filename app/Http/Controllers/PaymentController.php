<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments
     */
    public function index()
    {
        $payments = Payment::with('order.user', 'order.menu')->latest()->paginate(10);
        return view('payment.index', compact('payments'));
    }

    /**
     * Show the form for processing payment for an order
     */
    public function create(Order $order)
    {
        $payment = $order->payment;
        if (!$payment) {
            return redirect()->route('order.show', $order)->withErrors('No payment record found for this order.');
        }
        return view('payment.create', compact('order', 'payment'));
    }

    /**
     * Process the payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount_paid' => 'required|numeric|min:0.01',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $payment = $order->payment;

        if (!$payment) {
            return back()->withErrors('No payment record found for this order.');
        }

        // Calculate new amounts
        $newAmountPaid = $payment->amount_paid + $validated['amount_paid'];
        $newRemainingBalance = $payment->amount_due - $newAmountPaid;
        $changeAmount = $newAmountPaid > $payment->amount_due ? $newAmountPaid - $payment->amount_due : 0;

        // Determine payment status
        if ($newRemainingBalance <= 0) {
            $status = 'Paid';
            $newRemainingBalance = 0;
        } else {
            $status = 'Partial';
        }

        // Update payment record
        $payment->update([
            'amount_paid' => $newAmountPaid,
            'remaining_balance' => max(0, $newRemainingBalance),
            'status' => $status,
            'change' => $changeAmount,
            'payment_date' => now(),
        ]);

        // Update order status to Completed if payment is fully paid
        if ($status === 'Paid') {
            $order->update(['status' => 'Completed']);
        }

        return redirect()->route('payment.show', $payment)->with('success', 'Payment processed successfully.');
    }

    /**
     * Display the payment details
     */
    public function show(Payment $payment)
    {
        $payment->load('order.user', 'order.menu');
        return view('payment.show', compact('payment'));
    }

    /**
     * Display payment history for an order
     */
    public function history(Order $order)
    {
        $payment = $order->payment;
        if (!$payment) {
            return redirect()->route('order.show', $order)->withErrors('No payment record found for this order.');
        }
        return view('payment.history', compact('order', 'payment'));
    }
}