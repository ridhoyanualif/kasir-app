<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Edit Product</h3>

                    <form action="{{ route('products.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Product Barcode</label>
                                <input type="text" name="name" value="{{ $product->barcode }}" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Product Name</label>
                                <input type="text" name="name" value="{{ $product->name }}" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Expired Date</label>
                                <input type="date" name="expired_date" value="{{ $product->expired_date }}" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Stock</label>
                                <input type="number" name="stock" value="{{ $product->stock }}" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Modal</label>
                                <input type="number" name="modal" value="{{ $product->modal }}" required id="modal" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Selling Price</label>
                                <input type="number" name="selling_price" value="{{ $product->selling_price }}" required id="selling_price" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Profit (Auto Calculate)</label>
                                <input type="number" name="profit" value="{{ $product->profit }}" id="profit" readonly class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Category</label>
                                <select name="fid_category" required class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_category }}" {{ $product->fid_category == $category->id_category ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Description</label>
                                <textarea name="description" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
            <label class="block text-sm font-medium text-white">Photo</label>
            <input type="file" name="photo" id="imageUpload" accept="image/*"
                   class="mt-1 block w-full px-3 py-2 bg-gray-700 text-white rounded">
            <div id="imagePreview" class="mt-2">
                @if ($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" class="w-40 rounded shadow-md">
                @else
                    <span class="text-gray-400">No image selected</span>
                @endif
            </div>
            @if ($product->photo)
    <div class="mt-2 flex items-center space-x-2">
        <input type="checkbox" name="remove_photo" id="remove_photo" class="text-red-600">
        <label for="remove_photo" class="text-sm text-white">Delete product photo</label>
    </div>
@endif

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                            <a href="{{ route('products.index') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Cancel</a>
                        </div>
                    </form>

                    <script>
                        document.getElementById('modal').addEventListener('input', calculateProfit);
                        document.getElementById('selling_price').addEventListener('input', calculateProfit);

                        function calculateProfit() {
                            let modal = parseFloat(document.getElementById('modal').value) || 0;
                            let sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
                            document.getElementById('profit').value = sellingPrice - modal;
                        }
                    </script>

<script>
    const input = document.getElementById('imageUpload');
    const preview = document.getElementById('imagePreview');

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            preview.innerHTML = `
                <div class="relative mt-2 w-40">
                    <img src="${URL.createObjectURL(file)}" class="rounded shadow-md">
                    <button onclick="removeImage()" class="absolute top-0 right-0 bg-red-600 text-white px-2 py-1 rounded-full">×</button>
                </div>
            `;
        }
    });

    function removeImage() {
        input.value = '';
        preview.innerHTML = '<span class="text-gray-400">No image selected</span>';
    }
</script>
                </div>
            </div>
        </div>
    </div>
</x-layout>
