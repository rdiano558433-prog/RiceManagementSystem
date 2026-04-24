<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    Welcome back, <strong>{{ Auth::user()->name }}</strong>! 👋
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Products Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-gray-600 text-sm font-medium mb-2">Total Products</div>
                        <div class="text-3xl font-bold text-green-600">{{ \App\Models\Menu::count() }}</div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-gray-600 text-sm font-medium mb-2">Total Orders</div>
                        <div class="text-3xl font-bold text-blue-600">{{ \App\Models\Order::count() }}</div>
                    </div>
                </div>

                <!-- Pending Orders Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-gray-600 text-sm font-medium mb-2">Pending Orders</div>
                        <div class="text-3xl font-bold text-yellow-600">{{ \App\Models\Order::where('status', 'Pending')->count() }}</div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-gray-600 text-sm font-medium mb-2">Total Revenue</div>
                        <div class="text-3xl font-bold text-purple-600">₱{{ number_format(\App\Models\Order::where('status', 'Completed')->sum('total_cost'), 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Order #</th>
                                    <th class="px-4 py-2 text-left">Customer</th>
                                    <th class="px-4 py-2 text-left">Product</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Order::with(['user', 'menu'])->latest('order_date')->limit(5)->get() as $order)
                                    <tr class="border-t">
                                        <td class="px-4 py-2"><a href="{{ route('order.show', $order) }}" class="text-blue-600 hover:underline">{{ $order->order_number }}</a></td>
                                        <td class="px-4 py-2">{{ $order->user->name }}</td>
                                        <td class="px-4 py-2">{{ $order->menu->name }}</td>
                                        <td class="px-4 py-2">₱{{ number_format($order->total_cost, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold @if($order->status == 'Completed') bg-green-100 text-green-800 @elseif($order->status == 'Processing') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-t">
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No orders yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>