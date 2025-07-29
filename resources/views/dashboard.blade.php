<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cashier Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>

            </div>
        </div>
    </div>

    @if ($errors->any())
        <div id="error-message" class="bg-red-500 text-white p-4 rounded mt-6 mb-4 transition-opacity duration-1000">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- from cart controller --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6 ml-6 mr-6">
        @foreach ($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-4">
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="Product Photo"
                        class="w-full h-40 object-cover rounded-md mb-4">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>

                    @if ($product->selling_price_before !== null)
                        <p class="text-orange-500 font-bold text-xs line-through">Rp.
                            {{ number_format($product->selling_price_before, 2, ',', '.') }}</p>
                        <p class="text-yellow-500 font-bold text-sm">{{ $product->discount->cut }}%
                            {{ $product->discount->name }}</p>
                    @endif

                    <p class="text-green-500 font-bold">Rp. {{ number_format($product->selling_price, 2, ',', '.') }}
                    </p>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        Kategori: <span class="italic">{{ $product->category->name ?? 'Tidak ada' }}</span>
                    </p>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Ketersediaan: {{ $product->stock }}</p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-2 overflow-hidden">
                        {{ $product->description }}
                    </p>

                    <a href="{{ route('add.to.cart', $product->id_product) }}"
                        class="block mt-4 text-center bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded">
                        Add to cart
                    </a>
                </div>
        @endforeach
    </div>



    <!-- Floating Cart Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <div class="relative">
            <!-- Cart Toggle Button -->
            <button id="cart-toggle" type="button"
                class="bg-indigo-600 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-2 hover:bg-indigo-700 focus:outline-none">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16 11V9H4v2h12zm0 2H4v2h12v-2zM4 5h12v2H4V5z" />
                </svg>
                <span>Cart</span>
                <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ array_sum(array_column((array) session('cart'), 'quantity')) }}
                </span>
            </button>


            <div id="cart-dropdown"
                class="hidden absolute bottom-full right-0 mb-4 w-80 bg-white dark:bg-gray-800 text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-h-[400px] overflow-y-auto">

                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-lg">Your Cart</span>
                    @php
                        $cart = session('cart') ?? [];
                        $totalItems = array_sum(array_column($cart, 'quantity'));
                    @endphp
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $totalItems }} items</span>
                </div>

                @php $total = 0; @endphp
                @if (session('cart') && count(session('cart')))
                    <div class="space-y-4">
                        @foreach (session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp
                            <div class="flex items-start space-x-3 border-b pb-3">
                                <img src="{{ $details['image'] }}" alt="Product Image"
                                    class="w-14 h-14 object-cover rounded-md border">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold">{{ $details['name'] }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Price: Rp.
                                        {{ number_format($details['price'], 0, ',', '.') }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <form action="{{ route('cart.decrease') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit"
                                                class="text-blue-600 text-center w-4 rounded text-xs dark:bg-gray-700 dark:text-white">-</button>
                                        </form>
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}"
                                            min="1" readonly
                                            class="w-8 text-center text-xs border rounded px-1 py-0.5 dark:bg-gray-700 dark:text-white">
                                        <a href="{{ route('add.to.cart', $id) }}"
                                            class="text-blue-600 text-center w-4 rounded text-xs dark:bg-gray-700 dark:text-white">+</a>
                                    </div>
                                </div>
                                <form action="{{ route('remove.from.cart') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button type="submit"
                                        class="text-red-500 text-xs hover:underline ml-2">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">Your cart is empty.</p>
                @endif

                <div class="mt-4 border-t pt-3">
                    <p class="text-right font-semibold">Total: Rp. {{ number_format($total, 0, ',', '.') }}</p>
                    @if (session('cart') && count(session('cart')))
                        <form action="{{ route('pos.from.cart') }}" method="POST">
                            @csrf
                            @foreach (session('cart') as $id => $details)
                                <input type="hidden" name="products[{{ $id }}][id]"
                                    value="{{ $id }}">
                                <input type="hidden" name="products[{{ $id }}][name]"
                                    value="{{ $details['name'] }}">
                                <input type="hidden" name="products[{{ $id }}][price]"
                                    value="{{ $details['price'] }}">
                                <input type="hidden" name="products[{{ $id }}][quantity]"
                                    value="{{ $details['quantity'] }}">
                            @endforeach
                            <button type="submit"
                                class="block mt-2 w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                                Checkout
                            </button>
                        </form>
                    @else
                        <button
                            class="block mt-2 w-full text-center bg-indigo-400 text-white py-2 rounded cursor-not-allowed"
                            disabled>
                            Checkout
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Toggle Dropdown Script -->
    <script>
        const toggleButton = document.getElementById('cart-toggle');
        const dropdown = document.getElementById('cart-dropdown');

        toggleButton.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && !toggleButton.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Open cart if session('open_cart') is set
        @if (session('open_cart'))
            dropdown.classList.remove('hidden');
        @endif
    </script>
</x-app-layout>
