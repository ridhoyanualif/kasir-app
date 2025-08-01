<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>


    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg" id="transaction-container">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Transactions History List</h3>

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-3 mb-4 rounded" x-data="{ show: true }"
                            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            x-transition:leave="transition-opacity duration-700 ease-out"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form class="mb-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
                        <div class="flex flex-col">
                            <label for="monthInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Bulan
                            </label>
                            <input type="month" name="month" id="monthInput"
                                value="{{ request('month', $month ?? now()->format('Y-m')) }}"
                                onchange="this.form.submit()"
                                class="mt-1 w-48 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <div class="flex flex-col">
                            <label for="weekInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Minggu
                            </label>
                            <input type="week" name="week" id="weekInput"
                                value="{{ request('week', $week ?? '') }}" onchange="this.form.submit()"
                                class="mt-1 w-48 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>


                    </form>
                    @if (isset($startDate) && isset($endDate))
                        <p
                            class="w-full mt-3 md:mt-0 text-sm text-gray-600 dark:text-gray-400 font-medium md:self-center">
                            {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                        </p>
                    @endif
                    <div class="mb-4 flex justify-end">
                        <button onclick="downloadPDF()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                            Download PDF
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded">
                        <table class="min-w-full text-sm text-left border border-white">
                            <thead
                                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 uppercase tracking-wider text-xs border-b border-white">
                                <tr>
                                    <th class="px-4 py-3 border border-white">ID</th>
                                    <th class="px-4 py-3 border border-white">Invoice</th>
                                    <th class="px-4 py-3 border border-white">DateTime</th>
                                    <th class="px-4 py-3 border border-white">Cashier</th>
                                    <th class="px-4 py-3 border border-white">Total</th>
                                    <th class="px-4 py-3 border border-white">Modal</th>
                                    <th class="px-4 py-3 border border-white">Profit</th>
                                    <th class="px-4 py-3 border border-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-white">
                                        <td class="px-4 py-2 border border-white">{{ $transaction->id }}</td>
                                        <td class="px-4 py-2 border border-white">{{ $transaction->invoice ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 border border-white">
                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-4 py-2 border border-white">
                                            <div
                                                class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-100 dark:text-blue-800 px-3 rounded-full font-semibold shadow">
                                                {{ $transaction->user_id }} - {{ $transaction->user->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border border-white">Rp
                                            {{ number_format($transaction->total_price_after, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 border border-white">Rp
                                            {{ number_format($transaction->total_modal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 border border-white">Rp
                                            {{ number_format($transaction->net_profit, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 border border-white flex gap-2">
                                            <a href="{{ route('history.show', $transaction->id) }}"
                                                class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium">
                                                Show
                                            </a>
                                            <form action="{{ route('history.destroy', $transaction->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-block px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-white">
                                    <td class="px-4 py-2 border border-white gap-2">{{ $totalTransactions }}</td>
                                    <td colspan="3" class="px-4 py-2 border border-white gap-2 text-center">Jumlah
                                    </td>
                                    <td class="px-4 py-2 border border-white gap-2 bg-orange-600"> Rp
                                        {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 border border-white gap-2 bg-yellow-600"> Rp
                                        {{ number_format($totalModal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 border border-white gap-2 bg-green-600"> Rp
                                        {{ number_format($totalProfit, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($transactions->isEmpty())
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">No transactions found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        function downloadPDF() {
            const element = document.getElementById('transaction-container');

            // Save original class and style of the main container
            const originalContainer = {
                className: element.className,
                style: element.getAttribute('style')
            };

            // Store original class and style for all child elements
            const originalStates = [];
            element.querySelectorAll('*').forEach(el => {
                originalStates.push({
                    el,
                    className: el.className,
                    style: el.getAttribute('style')
                });

                // Remove dark: classes
                el.className = el.className
                    .split(' ')
                    .filter(cls => !cls.startsWith('dark:'))
                    .join(' ');

                // Force white background and black text
                el.style.backgroundColor = '#ffffff';
                el.style.color = '#000000';
                el.style.borderColor = '#000000';
            });

            // Apply same forced styles to the container itself
            element.className = element.className
                .split(' ')
                .filter(cls => !cls.startsWith('dark:'))
                .join(' ');
            element.style.backgroundColor = '#ffffff';
            element.style.color = '#0000';

            // Hide the Actions <th>
            const headerRow = element.querySelector('thead tr');
            const actionsTh = headerRow?.querySelector('th:last-child');
            if (actionsTh) actionsTh.style.display = 'none';

            // Hide all Actions td in tbody
            const tbodyRows = element.querySelectorAll('tbody tr');
            tbodyRows.forEach(row => {
                const actionsCell = row.querySelector('td:last-child');
                if (actionsCell) actionsCell.style.display = 'none';
            });

            // Hide the Download PDF button
            const downloadButton = element.querySelector('button[onclick="downloadPDF()"]');
            const downloadButtonContainer = downloadButton?.closest('div');
            if (downloadButtonContainer) downloadButtonContainer.style.display = 'none';

            const datePeriod =
                "{{ isset($startDate) && isset($endDate) ? $startDate->format('d-M-Y') . '-' . $endDate->format('d-M-Y') : 'report' }}";

            const options = {
                margin: [0, 0, 0, 0],
                filename: `Transaction-History-${datePeriod}.pdf`,
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
                // Restore main container styles
                element.className = originalContainer.className;
                if (originalContainer.style !== null) {
                    element.setAttribute('style', originalContainer.style);
                } else {
                    element.removeAttribute('style');
                }

                // Restore all child styles
                originalStates.forEach(({
                    el,
                    className,
                    style
                }) => {
                    el.className = className;
                    if (style !== null) {
                        el.setAttribute('style', style);
                    } else {
                        el.removeAttribute('style');
                    }
                });

                // Restore Actions column
                if (actionsTh) actionsTh.style.display = '';
                tbodyRows.forEach(row => {
                    const actionsCell = row.querySelector('td:last-child');
                    if (actionsCell) actionsCell.style.display = '';
                });

                // Restore Download button
                if (downloadButtonContainer) downloadButtonContainer.style.display = '';
            });
        }
    </script>



</x-app-layout>
