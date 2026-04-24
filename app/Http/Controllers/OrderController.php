<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders
     */
    public function index()
    {
        $orders = Order::with(['user', 'menu', 'payment'])->latest('order_date')->paginate(10);
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $menus = Menu::all();
        $users = User::all();
        return view('order.create', compact('menus', 'users'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);

        // Check stock availability
        if ($menu->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock available. Available: ' . $menu->stock . ' kg'])->withInput();
        }

        // Calculate total cost
        $totalCost = $validated['quantity'] * $menu->price_per_kilo;
        $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);

        // Create order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $validated['user_id'],
            'menu_id' => $validated['menu_id'],
            'quantity' => $validated['quantity'],
            'price_per_kilo' => $menu->price_per_kilo,
            'total_cost' => $totalCost,
            'order_date' => now(),
        ]);

        // Create associated payment record
        $paymentNumber = 'PAY-' . date('YmdHis') . '-' . rand(1000, 9999);
        Payment::create([
            'payment_number' => $paymentNumber,
            'order_id' => $order->id,
            'amount_due' => $totalCost,
            'remaining_balance' => $totalCost,
        ]);

        // Update menu stock
        $menu->decrement('stock', $validated['quantity']);

        return redirect()->route('order.show', $order)->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'menu', 'payment']);
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the order status
     */
    public function edit(Order $order)
    {
        return view('order.edit', compact('order'));
    }

    /**
     * Update the order status
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Completed',
        ]);

        $order->update($validated);

        return redirect()->route('order.index')->with('success', 'Order status updated successfully.');
    }

    /**
     * Delete the specified order
     */
    public function destroy(Order $order)
    {
        // Restore stock
        $order->menu->increment('stock', $order->quantity);

        // Delete associated payment
        if ($order->payment) {
            $order->payment->delete();
        }

        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
    }
}