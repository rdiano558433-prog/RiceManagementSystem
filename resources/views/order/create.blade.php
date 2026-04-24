<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('order.store') }}" method="POST" id="orderForm" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Customer <span class="text-red-500">*</span></label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 @error('user_id') border-red-500 @endif" required>
                                    <option value="">Select Customer</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="menu_id" class="block text-sm font-medium text-gray-700">Product <span class="text-red-500">*</span></label>
                                <select name="menu_id" id="menu_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 @error('menu_id') border-red-500 @endif" required onchange="updatePrice()">
                                    <option value="">Select Product</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-price="{{ $menu->price_per_kilo }}" data-stock="{{ $menu->stock }}"
                                                @if(old('menu_id') == $menu->id) selected @endif>
                                            {{ $menu->name }} (₱{{ number_format($menu->price_per_kilo, 2) }}/kg) - Stock: {{ $menu->stock }}kg
                                        </option>
                                    @endforeach
                                </select>
                                @error('menu_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity (kg) <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity" min="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 @error('quantity') border-red-500 @endif" value="{{ old('quantity') }}" required onchange="calculateTotal()">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price per Kilo</label>
                                <input type="text" id="pricePerKilo" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Cost</label>
                                <input type="text" id="totalCost" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-100 font-bold text-lg" readonly>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                Create Order
                            </button>
                            <a href="{{ route('order.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updatePrice() {
        const menuSelect = document.getElementById('menu_id');
        const selected = menuSelect.options[menuSelect.selectedIndex];
        const price = selected.getAttribute('data-price') || 0;
        document.getElementById('pricePerKilo').value = '₱' + parseFloat(price).toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const pricePerKilo = parseFloat(document.getElementById('pricePerKilo').value.replace('₱', '')) || 0;
        const total = quantity * pricePerKilo;
        document.getElementById('totalCost').value = '₱' + total.toFixed(2);
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', updatePrice);
    </script>
</x-app-layout>