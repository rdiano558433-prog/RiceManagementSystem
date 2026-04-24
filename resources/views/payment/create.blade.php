<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Order Summary</h3>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Order Number</p>
                            <p class="font-semibold">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Customer</p>
                            <p class="font-semibold">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Product</p>
                            <p class="font-semibold">{{ $order->menu->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Quantity</p>
                            <p class="font-semibold">{{ $order->quantity }} kg</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Price per Kilo</p>
                            <p class="font-semibold">₱{{ number_format($order->price_per_kilo, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Cost</p>
                            <p class="font-bold text-lg text-green-600">₱{{ number_format($order->total_cost, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Payment Details</h3>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-gray-50 rounded text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Amount Due</p>
                                <p class="font-semibold">₱{{ number_format($payment->amount_due, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Previous Payment</p>
                                <p class="font-semibold">₱{{ number_format($payment->amount_paid, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Remaining Balance</p>
                                <p class="font-bold text-red-600">₱{{ number_format($payment->remaining_balance, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Current Status</p>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold @if($payment->status == 'Paid') bg-green-100 text-green-800 @elseif($payment->status == 'Partial') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $payment->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.store') }}" method="POST" id="paymentForm" class="space-y-6">
                        @csrf

                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <div>
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount to Pay (₱) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0.01" placeholder="0.00" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 @error('amount_paid') border-red-500 @endif" required onchange="calculateChange()">
                            @error('amount_paid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Change</label>
                                <input type="text" id="change" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Remaining Balance</label>
                                <input type="text" id="newBalance" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-100 font-bold" readonly>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                Process Payment
                            </button>
                            <a href="{{ route('order.show', $order) }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function calculateChange() {
        const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
        const remainingBalance = parseFloat('{{ $payment->remaining_balance }}');
        
        const change = amountPaid - remainingBalance;
        const newBalance = remainingBalance - amountPaid;

        document.getElementById('change').value = change > 0 ? '₱' + change.toFixed(2) : '₱0.00';
        document.getElementById('newBalance').value = newBalance > 0 ? '₱' + newBalance.toFixed(2) : '₱0.00';
    }
    </script>
</x-app-layout>