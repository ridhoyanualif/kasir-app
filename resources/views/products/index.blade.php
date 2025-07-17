<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Products Table') }}

                    <!-- Tabel Products -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2">ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Barcode</th>
                                    <th class="border border-gray-300 px-4 py-2">Photo</th>
                                    <th class="border border-gray-300 px-4 py-2">Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Expired Date</th>
                                    <th class="border border-gray-300 px-4 py-2">Stock</th>
                                    <th class="border border-gray-300 px-4 py-2">Modal</th>
                                    <th class="border border-gray-300 px-4 py-2">Selling Price</th>
                                    <th class="border border-gray-300 px-4 py-2">Selling Price Before</th>
                                    <th class="border border-gray-300 px-4 py-2">Discount ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Profit</th>
                                    <th class="border border-gray-300 px-4 py-2">Category ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Description</th>
                                    <th class="border border-gray-300 px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 px-4 py-2">{{ $product->id_product }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $product->barcode }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                                            @if ($product->photo)
                                                <img src="{{ asset('storage/' . $product->photo) }}" alt="Product Photo"
                                                    class="w-18 h-18 rounded object-cover">
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                                            {{ $product->expired_date ?? '-' }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $product->stock }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ 'Rp. ' . number_format($product->modal, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ 'Rp. ' . number_format($product->selling_price, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                                            {{ $product->selling_price_before === null ? '-' : 'Rp. ' . number_format($product->selling_price_before, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                                            @if ($product->discount)
                                                <span
                                                    class="bg-green-600 text-gray-800 text-sm font-semibold px-3 py-1 rounded-full">
                                                    {{ $product->fid_discount }} - {{ $product->discount->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-white text-sm font-semibold px-3 py-1 rounded-full">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ 'Rp. ' . number_format($product->profit, 2, ',', '.') }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                                            @if ($product->category)
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                                    {{ $product->fid_category }} - {{ $product->category->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="bg-gray-100 text-gray-600 text-sm font-semibold px-3 py-1 rounded-full">
                                                    -
                                                </span>
                                            @endif
                                        </td>

                                        <td class="border border-gray-300 px-4 py-2">{{ $product->description }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('products.edit', $product->id_product) }}"
                                                    class="px-2 py-1 bg-blue-600 text-white rounded">Edit</a>

                                                <form action="{{ route('products.destroy', $product->id_product) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Akhir Tabel Products -->

                    @if ($errors->any())
                        <div id="error-message"
                            class="bg-red-500 text-white p-4 rounded mt-6 mb-4 transition-opacity duration-1000">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div id="success-message"
                            class="bg-green-500 text-white p-4 rounded mt-6 mb-4 transition-opacity duration-1000">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Form Tambah Produk -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Add Product</h3>
                        <form action="{{ route('products.store') }}" method="POST" class="mt-4"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Area Scanner Webcam -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white">Scan Barcode via Webcam</label>
                                <div id="qr-reader" style="width: 600px"></div>
                            </div>



                            <div class="mb-4">
                                <label for="barcode" class="block text-sm font-medium text-white">Barcode
                                    Result</label>
                                <input type="text" name="barcode" id="barcodeInput" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                <span id="barcodeFeedback" class="text-sm mt-1 block"></span>
                            </div>




                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Product Name</label>
                                    <input type="text" name="name" required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>



                                <div>
                                    <label class="block text-sm font-medium">Expired Date</label>
                                    <input type="date" name="expired_date"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Stock</label>
                                    <input type="number" name="stock" required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Modal</label>
                                    <input type="number" name="modal" required id="modal"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Selling Price</label>
                                    <input type="number" name="selling_price" required id="selling_price"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Profit (Auto Calculate)</label>
                                    <input type="number" name="profit" id="profit" readonly
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Category</label>
                                    <select name="fid_category" required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id_category }}">{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Description</label>
                                    <textarea name="description"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-white">Photo</label>
                                    <input type="file" name="photo" id="imageUpload" accept="image/*" required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm bg-gray-700 text-white">
                                    <div id="imagePreview" class="mt-2 text-gray-400">No image selected</div>
                                </div>
                            </div>

                            <button type="submit"
                                class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Submit
                            </button>
                        </form>



                        <script>
                            setTimeout(() => {
                                const errorBox = document.getElementById('error-message');
                                const successBox = document.getElementById('success-message');
                                if (errorBox) {
                                    errorBox.style.opacity = '0';
                                    setTimeout(() => errorBox.remove(), 1500);
                                }
                                if (successBox) {
                                    successBox.style.opacity = '0';
                                    setTimeout(() => successBox.remove(), 1500);
                                }
                            }, 1000); // hilang setelah 1 detik
                        </script>



                        <script>
                            document.getElementById('modal').addEventListener('input', calculateProfit);
                            document.getElementById('selling_price').addEventListener('input', calculateProfit);

                            function calculateProfit() {
                                let modal = parseFloat(document.getElementById('modal').value) || 0;
                                let sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
                                document.getElementById('profit').value = sellingPrice - modal;
                            }
                        </script>
                        <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
                        <script>
                            function onScanSuccess(decodedText, decodedResult) {
                                console.log(`Code scanned = ${decodedText}`, decodedResult);
                                const barcodeInput = document.getElementById('barcodeInput');
                                if (barcodeInput) {
                                    barcodeInput.value = decodedText; // Set input value to scanned barcode
                                } else {
                                    console.error("Element with ID 'barcodeInput' not found!");
                                }
                            }
                            var html5QrcodeScanner = new Html5QrcodeScanner(
                                "qr-reader", {
                                    fps: 10,
                                    qrbox: 250
                                });
                            html5QrcodeScanner.render(onScanSuccess);
                        </script>

                        <script>
                            const input = document.getElementById('imageUpload');
                            const preview = document.getElementById('imagePreview');

                            input.addEventListener('change', function() {
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
                    <!-- Akhir Form Tambah Produk -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
