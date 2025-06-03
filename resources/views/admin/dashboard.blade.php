<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="pt-12 pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Products -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Products</h3>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                        {{ $totalProducts }}
                    </p>
                </div>

                <!-- Total Members -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Members</h3>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                        {{ $totalMembers }}
                    </p>
                </div>

                <!-- Example: Total Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Categories</h3>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                        {{ $totalCategories }}
                    </p>
                </div>

                <!-- Example: Total Cashiers -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Cashiers</h3>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                        {{ $totalCashiers }}
                    </p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto mt-8 flex">
                <div class="w-50 flex justify-end bg-white dark:bg-gray-800 rounded-t-lg p-3 ml-auto">
                    <button onclick="downloadPDF()"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Download PDF
                    </button>
                </div>
            </div>



            <div class="bg-white dark:bg-gray-800 rounded-b-lg rounded-tl-lg shadow p-6" id="selling-report">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Selling Chart</h3>

                <form method="GET" class="mb-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <!-- Input Bulan -->
                    <div class="flex flex-col">
                        <label for="monthInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Bulan
                        </label>
                        <input type="month" name="month" id="monthInput"
                            value="{{ request('month', $month ?? '') }}" onchange="this.form.submit()"
                            class="mt-1 w-48 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Input Minggu -->
                    <div class="flex flex-col">
                        <label for="weekInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Minggu
                        </label>
                        <input type="week" name="week" id="weekInput" value="{{ request('week', $week ?? '') }}"
                            onchange="this.form.submit()"
                            class="mt-1 w-48 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>


                </form>


                <!-- Tanggal Range -->
                @if (isset($startDate) && isset($endDate))
                    <p class="w-full mt-3 md:mt-0 text-sm text-gray-600 dark:text-gray-400 font-medium md:self-center">
                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </p>
                @endif


                <!-- Chart Canvas -->
                <canvas id="salesChart" height="100"></canvas>

                <!-- Profit Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 rounded-b-lg shadow p-6">
                    <!-- Laba Kotor -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Laba Kotor</h3>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                            Rp {{ number_format($grossProfit, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Modal -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Modal</h3>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                            Rp {{ number_format($totalCost, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Keuntungan Bersih -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Keuntungan Bersih</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Profit Level -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profit Level</h3>
                        <p class="text-3xl font-bold mt-2 {{ $profitColor }}">
                            {{ number_format($profitLevel, 2) }}% {{ $profitLabel }}
                        </p>
                    </div>

                </div>
            </div>

        </div>



        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const salesCtx = document.getElementById('salesChart').getContext('2d');

            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dates) !!}, // Tanggal
                    datasets: [{
                        label: 'Total Penjualan Harian',
                        data: {!! json_encode($totals) !!},
                        borderColor: 'rgba(22, 163, 74, 1)', // Hijau solid (green-600)
                        backgroundColor: 'rgba(22, 163, 74, 0.2)', // Hijau transparan
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(22, 163, 74, 1)', // Titik hijau solid
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 90,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'rgba(22, 163, 74, 1)'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>


        <script>
            function downloadPDF() {
                const chartCanvas = document.getElementById('salesChart');
                const reportElement = document.getElementById('selling-report');

                // Ganti canvas chart jadi gambar
                const chartImg = document.createElement('img');
                chartImg.src = chartCanvas.toDataURL("image/png");
                chartImg.alt = 'Sales Chart';
                chartImg.style.width = '100%';
                chartImg.style.maxHeight = '400px';

                chartCanvas.parentNode.replaceChild(chartImg, chartCanvas); // Ganti canvas dengan img

                const htmlContent = reportElement.outerHTML;
                const transactionId = new Date().getTime();

                fetch("{{ route('admin.generate-pdf') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            html: htmlContent,
                            transaction_id: transactionId
                        })
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        const blobUrl = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = blobUrl;
                        a.download = `Selling-Report-${transactionId}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        location.reload(); // Reload untuk mengembalikan canvas
                    })
                    .catch(error => {
                        console.error("Gagal menghasilkan PDF:", error);
                    });
            }
        </script>



</x-app-layout>
