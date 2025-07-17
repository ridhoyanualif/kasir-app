<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Discounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg relative">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Discounts Table') }}

                    @if ($errors->any())
                        <div id="error-message"
                            class="bg-red-500 text-white p-4 rounded mt-6 mb-4 transition-opacity duration-1000">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2">ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Description</th>
                                    <th class="border border-gray-300 px-4 py-2">Cut</th>
                                    <th class="border border-gray-300 px-4 py-2">Start Date</th>
                                    <th class="border border-gray-300 px-4 py-2">End Date</th>
                                    <th class="border border-gray-300 px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $discount)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->id }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->description }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->cut }}%</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->start_datetime }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $discount->end_datetime }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="{{ route('discounts.edit', $discount->id) }}"
                                                class="bg-blue-500 text-white px-3 py-1 rounded">Edit</a>
                                            <form action="{{ route('discounts.destroy', $discount->id) }}"
                                                method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-6 mt-6">
                        <h3 class="text-lg font-semibold">Add Discount</h3>
                        <form action="{{ route('admin.discounts.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium">Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium">Description</label>
                                <input type="text" name="description" id="description" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="mb-4">
                                <label for="discount" class="block text-sm font-medium">Discount</label>
                                <div class="relative">
                                    <input type="number" name="cut" id="discount" required min="1"
                                        max="100"
                                        class="mt-1 block w-full px-3 py-2 pr-10 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                                        placeholder="15" oninput="this.value = Math.max(1, Math.min(100, this.value))">
                                    <span
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300">%</span>
                                </div>
                            </div>
                            <div x-data="{ open: false, selected: null }" class="relative">
                                <label class="block text-sm font-medium">Product</label>
                                <!-- Dropdown Button -->
                                <button @click="open = !open" type="button"
                                    class="w-full px-3 py-2 border rounded-md bg-gray-700 text-white text-left flex items-center space-x-2">
                                    <template x-if="selected">
                                        <img :src="selected.photo" alt=""
                                            class="w-6 h-6 rounded object-cover" />
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
                                <input type="datetime-local" name="start_date"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">End Date</label>
                                <input type="datetime-local" name="end_date"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>



                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>
