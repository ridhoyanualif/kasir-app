<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Edit Category</h3>
                    
                    <!-- Form Edit Kategori -->
                    <form action="{{ route('categories.update', $category->id_category) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT') <!-- Menyatakan bahwa ini adalah permintaan PUT -->
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white">Category Name</label>
                            <input type="text" name="name" id="name" value="{{ $category->name }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @if ($errors->has('name'))
    <p class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</p>
@endif

                        </div>
                        
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                    </form>
                    <!-- Akhir Form Edit Kategori -->

                </div>
            </div>
        </div>
    </div>
</x-layout>
