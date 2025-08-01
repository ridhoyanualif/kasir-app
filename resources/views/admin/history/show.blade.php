<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Detail') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- LEFT: Detail Transaksi -->
                <div class="lg:w-1/2 w-full">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-6">Detail Transaksi: {{ $transaction->invoice }}</h3>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-semibold">Invoice:</span>
                                    <p>{{ $transaction->invoice }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Cashier ID:</p>
                                    <div
                                        class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 px-3 rounded-full font-semibold shadow">
                                        {{ $transaction->user_id }} - {{ $transaction->user->name }}
                                    </div>
                                </div>
                                @if(isset($transaction->fid_member))
                                <div>
                                    <p class="font-semibold">Member ID:</p>
                                    <div class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-100 dark:text-blue-800 px-3 rounded-full font-semibold shadow">
                                        {{ $transaction->fid_member ?? '' }} - {{  $transaction->member->name ?? '' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="font-semibold">Point:</span>
                                    <p>{{ $transaction->point == 0 ? '-' : $transaction->point }} Pts.</p>
                                </div>
                                <div>
                                    <span class="font-semibold">Point After:</span>
                                    <p>{{ $transaction->point_after == 0 ? '0' : $transaction->point_after }} Pts.</p>
                                </div>
                                <div>
                                    <span class="font-semibold">Potongan:</span>
                                    <p>Rp {{ number_format($transaction->cut, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold">Total Sebelum Potongan:</span>
                                    <p>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="font-semibold">Total:</span>
                                    <p>Rp {{ number_format($transaction->total_price_after, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold">Cash:</span>
                                    <p>Rp {{ number_format($transaction->cash, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold">Change:</span>
                                    <p>Rp {{ number_format($transaction->change, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-semibold">Tanggal Transaksi:</span>
                                    <p>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 w-full" id="receipt-container">
                    <div class="bg-gray-100 dark:bg-white p-4 rounded-lg shadow text-gray-900 dark:text-gray-700">
                        <div class="mx-auto h-20 w-20 relative" id="logo-container">
                            <span class="absolute inset-0 rounded-full border-2 border-gray-800"></span>
                            <span class="absolute inset-2 rounded-full border-2 border-gray-400"></span>
                            <img src="{{ asset('images/Kasir.png') }}" alt="logo"
                                class="relative h-full w-full rounded-full object-cover p-2">
                        </div>
                        <h2 class="text-lg font-semibold my-2 text-center">Transaction Receipt</h2>
                        <hr class="mb-2 border-gray-800">
                        <p class="block text-sm font-medium"><strong>Cashier ID:</strong> {{ $transaction->user_id }} -
                            {{ $transaction->user->name }}</p>
                        <p class="block text-sm font-medium"><strong>Invoice:</strong> {{ $transaction->invoice }}</p>
                        <p class="block text-sm font-medium"><strong>Date:</strong>
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}</p>

                        @if ($transaction->fid_member)
                            <hr class="my-2 border-gray-800">
                            <p class="block text-sm font-medium"><strong>Member ID:</strong>
                                {{ $transaction->fid_member }} - {{ $transaction->member->name }}</p>
                            <p class="block text-sm font-medium"><strong>Point Before:</strong>
                                {{ $transaction->point }} Pts.</p>
                            <p class="block text-sm font-medium"><strong>Point After:</strong>
                                {{ $transaction->point_after }} Pts.</p>
                        @endif

                        <hr class="my-2 border-gray-800">
                        <h3 class="text-lg font-semibold">Items:</h3>
                        <table class="w-full border-collapse border border-gray-800 mt-2 text-sm">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="border border-gray-700 px-2 py-1 text-left">Product</th>
                                    <th class="border border-gray-700 px-2 py-1 text-center">Qty</th>
                                    <th class="border border-gray-700 px-2 py-1 text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="border border-gray-700 px-2 py-1">
                                            <strong>{{ $item->product->name }}</strong>
                                        </td>
                                        <td class="border border-gray-700 px-2 py-1 text-center">
                                            <strong>{{ $item->quantity }}</strong>
                                        </td>
                                        <td class="border border-gray-700 px-2 py-1 text-right"><strong>Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr class="my-2">
                        @if ($transaction->cut > 0)
                        <p><strong>Total Price Before Cut:</strong> <span class="text-blue-800">Rp
                                {{ number_format($transaction->total_price, 0, ',', '.') }}</span></p>
                            <p><strong>Cut (Point):</strong> <span class="text-red-900">-Rp
                                    {{ number_format($transaction->cut, 0, ',', '.') }}</span></p>
                        @endif
                        @if ($transaction->total_price_after > 0)
                            <p><strong>Total Price:</strong> <span class="text-blue-900">Rp
                                    {{ number_format($transaction->total_price_after, 0, ',', '.') }}</span></p>
                        @endif
                        <p><strong>Cash:</strong> <span class="text-green-700">Rp
                                {{ number_format($transaction->cash, 0, ',', '.') }}</span></p>
                        <p><strong>Change:</strong> <span class="text-yellow-500">Rp
                                {{ number_format($transaction->change, 0, ',', '.') }}</span></p>
                    </div>
                </div>
                <div>
                    <button onclick="downloadPDF()" class="px-1 py-1 bg-blue-600 text-white rounded-lg w-full">
                        PDF Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        function downloadPDF() {
            const element = document.getElementById('receipt-container');

            // Simpan style awal
            const originalStyle = {
                width: element.style.width,
                maxWidth: element.style.maxWidth,
                margin: element.style.margin
            };

            // Terapkan style untuk PDF
            element.style.width = '100%';
            element.style.maxWidth = '800px';
            element.style.margin = '0 auto';

            const options = {
                margin: [0.5, 0.5, 0.5, 0.5],
                filename: '{{ $transaction->invoice }}_Receipt.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 4,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(options).from(element).save().then(() => {
                // Kembalikan style semula setelah PDF selesai dibuat
                element.style.width = originalStyle.width;
                element.style.maxWidth = originalStyle.maxWidth;
                element.style.margin = originalStyle.margin;
            });
        }
    </script>



</x-app-layout>
