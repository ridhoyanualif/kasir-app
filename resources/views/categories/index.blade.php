<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Categories Table</h3>

                    @if (session('error'))
                        <div class="bg-red-500 text-white p-3 mb-4 rounded transition-opacity duration-700 ease-out"
                             x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 2000)"
                             x-transition:leave="transition-opacity duration-700 ease-out"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tampilkan Pesan Sukses -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-3 mb-4 rounded" x-data="{ show: true }"
                        x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 2000)"
                             x-transition:leave="transition-opacity duration-700 ease-out"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tabel Categories -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2">ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 px-4 py-2">{{ $category->id_category }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $category->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="{{ route('categories.edit', $category->id_category) }}"
                                                class="bg-blue-500 text-white px-3 py-1 rounded">Edit</a>
                                            <form action="{{ route('categories.destroy', $category->id_category) }}"
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

                    <!-- Form Tambah Kategori -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Add Category</h3>
                        <form action="{{ route('categories.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white">Category Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @if ($errors->has('name'))
                                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</p>
                                @endif

                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
