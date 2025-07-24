<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Discounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Edit Discounts</h3>

                    <!-- Form Edit Discounts -->
                    <form action="{{ route('discounts.update', $discounts->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium">Name</label>
                            <input type="text" name="name" id="name" value="{{ $discounts->name }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium">Description</label>
                            <input type="text" name="description" id="description"
                                value="{{ $discounts->description }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="discount" class="block text-sm font-medium">Discount</label>
                            <div class="relative">
                                <input type="number" name="cut" id="discount" required min="1"
                                    max="100" value="{{ $discounts->cut }}"
                                    class="mt-1 block w-full px-3 py-2 pr-10 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                                    placeholder="15">
                                <span
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300">%</span>
                            </div>
                        </div>
                        <div x-data="{
                            open: false,
                            selected: {
                                id: '{{ $selectedProduct->id_product ?? '' }}',
                                name: '{{ $selectedProduct->name ?? '' }}',
                                selling_price: '{{ $selectedProduct->selling_price ?? '' }}',
                                photo: '{{ $selectedProduct->photo ?? '' }}'
                            }
                        }" class="relative">
                            <label class="block text-sm font-medium">Product</label>
                            <!-- Dropdown Button -->
                            <button @click="open = !open" type="button"
                                class="w-full px-3 py-2 border rounded-md bg-gray-700 text-white text-left flex items-center space-x-2">
                                <template x-if="selected">
                                    <img :src="selected.photo" alt="" class="w-6 h-6 rounded object-cover" />
                                </template>
                                <span
                                    x-text="selected ? selected.name + ' - Rp ' + selected.selling_price : 'Select a product'"></span>
                            </button>

                            <!-- Dropdown List -->
                            <ul x-show="open" @click.away="open = false"
                                class="absolute z-50 w-full bg-gray-700 border mt-1 rounded-md shadow overflow-y-auto max-h-96">
                                @foreach ($dropdowns as $d)
                                    <li @click="selected = {
                        id: '{{ $d->id_product }}',
                        name: '{{ $d->name }}',
                        selling_price: '{{ $d->selling_price }}',
                        photo: '{{ $d->photo }}'
                    }; open = false"
                                        class="flex items-center px-3 py-2 hover:bg-gray-600 cursor-pointer">
                                        <img src="{{ $d->photo }}" alt="{{ $d->name }}"
                                            class="w-8 h-8 mr-2 rounded object-cover">
                                        <span>{{ $d->name }} - Rp {{ $d->selling_price }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <input type="hidden" name="fid_product" :value="selected?.id">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Start Date</label>
                            <input type="datetime-local" name="start_date" value="{{ $discounts->start_datetime }}"
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">End Date</label>
                            <input type="datetime-local" name="end_date" value="{{ $discounts->end_datetime }}"
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                        </div>

                        <!-- Tombol Update -->
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                    </form>
                    <!-- Akhir Form Edit Discounts -->

                    <!-- Tombol Delete -->
                    <form action="{{ route('discounts.destroy', $discounts->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    <!-- Akhir Tombol Delete -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
