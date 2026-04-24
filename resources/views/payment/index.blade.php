<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Payment #</th>
                                    <th class="px-4 py-3 text-left font-semibold">Order #</th>
                                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                                    <th class="px-4 py-3 text-left font-semibold">Amount Due</th>
                                    <th class="px-4 py-3 text-left font-semibold">Amount Paid</th>
                                    <th class="px-4 py-3 text-left font-semibold">Balance</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium">{{ $payment->payment_number }}</td>
                                        <td class="px-4 py-3">{{ $payment->order->order_number }}</td>
                                        <td class="px-4 py-3">{{ $payment->order->user->name }}</td>
                                        <td class="px-4 py-3">₱{{ number_format($payment->amount_due, 2) }}</td>
                                        <td class="px-4 py-3">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="px-4 py-3">₱{{ number_format($payment->remaining_balance, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold @if($payment->status == 'Paid') bg-green-100 text-green-800 @elseif($payment->status == 'Partial') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('payment.show', $payment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                                    View
                                                </a>
                                                @if($payment->status != 'Paid')
                                                    <a href="{{ route('payment.create', $payment->order) }}" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                        Pay
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($payments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>