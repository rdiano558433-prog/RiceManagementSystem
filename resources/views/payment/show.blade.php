<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Receipt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">{{ $payment->payment_number }}</h3>
                </div>
                <div class="p-6 text-gray-900 space-y-6">
                    <!-- Header Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm">Order</p>
                            <p class="font-semibold">{{ $payment->order->order_number }}</p>
                            <p class="text-gray-600 text-sm mt-3">Customer</p>
                            <p class="font-semibold">{{ $payment->order->user->name }}</p>
                            <p class="text-gray-600 text-sm mt-3">Email</p>
                            <p class="font-semibold">{{ $payment->order->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Payment Date</p>
                            <p class="font-semibold">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i A') : 'N/A' }}</p>
                            <p class="text-gray-600 text-sm mt-3">Status</p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold @if($payment->status == 'Paid') bg-green-100 text-green-800 @elseif($payment->status == 'Partial') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                {{ $payment->status }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <!-- Order Items -->
                    <div>
                        <h4 class="font-semibold mb-3">Order Items</h4>
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
                                    <td class="px-4 py-3">{{ $payment->order->menu->name }}</td>
                                    <td class="px-4 py-3">{{ $payment->order->quantity }} kg</td>
                                    <td class="px-4 py-3">₱{{ number_format($payment->order->price_per_kilo, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold">₱{{ number_format($payment->order->total_cost, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Payment Summary -->
                    <div class="bg-gray-50 p-4 rounded">
                        <h4 class="font-semibold mb-3">Payment Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Due</span>
                                <span class="font-semibold">₱{{ number_format($payment->amount_due, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Paid</span>
                                <span class="font-semibold">₱{{ number_format($payment->amount_paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-600">Remaining Balance</span>
                                <span class="font-semibold text-red-600">₱{{ number_format($payment->remaining_balance, 2) }}</span>
                            </div>
                            @if($payment->change > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Change</span>
                                    <span class="font-semibold text-green-600">₱{{ number_format($payment->change, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-4">
                        @if($payment->status != 'Paid')
                            <a href="{{ route('payment.create', $payment->order) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Make Additional Payment
                            </a>
                        @endif
                        <a href="{{ route('payment.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                            Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>