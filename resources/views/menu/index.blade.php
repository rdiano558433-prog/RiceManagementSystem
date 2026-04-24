<!-- resources/views/menu/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Menu Management') }}
            </h2>
            <a href="{{ route('menu.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Add New Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Product Name</th>
                                    <th class="px-4 py-3 text-left font-semibold">Category</th>
                                    <th class="px-4 py-3 text-left font-semibold">Price per Kilo</th>
                                    <th class="px-4 py-3 text-left font-semibold">Stock</th>
                                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium">{{ $menu->name }}</td>
                                        <td class="px-4 py-3">{{ $menu->category }}</td>
                                        <td class="px-4 py-3">₱{{ number_format($menu->price_per_kilo, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold @if($menu->stock > 10) bg-green-100 text-green-800 @elseif($menu->stock > 0) bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $menu->stock }} kg
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($menu->description, 50) }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('menu.edit', $menu) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('menu.destroy', $menu) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            No products found. <a href="{{ route('menu.create') }}" class="text-blue-600 hover:underline">Create one</a>
                                        </td>
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