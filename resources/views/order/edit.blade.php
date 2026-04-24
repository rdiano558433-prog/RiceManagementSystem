<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Order Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Order</p>
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
                                <p class="text-gray-600">Total</p>
                                <p class="font-semibold">₱{{ number_format($order->total_cost, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <form action="{{ route('order.update', $order) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Order Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 @error('status') border-red-500 @endif" required>
                                <option value="">Select Status</option>
                                <option value="Pending" @if($order->status == 'Pending') selected @endif>Pending</option>
                                <option value="Processing" @if($order->status == 'Processing') selected @endif>Processing</option>
                                <option value="Completed" @if($order->status == 'Completed') selected @endif>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Update Status
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
</x-app-layout>