<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">{{ $order->order_number }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Order Date</p>
                                    <p class="font-semibold">{{ $order->order_date->format('M d, Y H:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Customer</p>
                                    <p class="font-semibold">{{ $order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Email</p>
                                    <p class="font-semibold">{{ $order->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Status</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold @if($order->status == 'Completed') bg-green-100 text-green-800 @elseif($order->status == 'Processing') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Order Items</h4>
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Product</th>
                                        <th class="px-4 py-2 text-left">Quantity</th>
                                        <th class="px-4 py-2 text-left">Price/kg</th>
                                        <th class="px-4 py-2 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t">
                                        <td class="px-4 py-3">{{ $order->menu->name }}</td>
                                        <td class="px-4 py-3">{{ $order->quantity }} kg</td>
                                        <td class="px-4 py-3">₱{{ number_format($order->price_per_kilo, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">₱{{ number_format($order->total_cost, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-4 text-right">
                                <p class="text-xl font-bold text-green-600">Total: ₱{{ number_format($order->total_cost, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Payment Information</h4>
                            @if($order->payment)
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Amount Due:</span>
                                        <span class="font-semibold">₱{{ number_format($order->payment->amount_due, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Amount Paid:</span>
                                        <span class="font-semibold">₱{{ number_format($order->payment->amount_paid, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Remaining:</span>
                                        <span class="font-semibold text-red-600">₱{{ number_format($order->payment->remaining_balance, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold @if($order->payment->status == 'Paid') bg-green-100 text-green-800 @elseif($order->payment->status == 'Partial') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                            {{ $order->payment->status }}
                                        </span>
                                    </div>
                                    @if($order->payment->change > 0)
                                        <div class="flex justify-between border-t pt-2">
                                            <span class="text-gray-600">Change:</span>
                                            <span class="font-semibold text-green-600">₱{{ number_format($order->payment->change, 2) }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($order->payment->status != 'Paid')
                                    <a href="{{ route('payment.create', $order) }}" class="mt-4 block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 rounded">
                                        Process Payment
                                    </a>
                                @endif
                            @endif

                            <div class="mt-4 space-y-2">
                                <a href="{{ route('order.edit', $order) }}" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center font-bold py-2 rounded">
                                    Update Status
                                </a>
                                <a href="{{ route('order.index') }}" class="block w-full bg-gray-400 hover:bg-gray-500 text-white text-center font-bold py-2 rounded">
                                    Back to Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>