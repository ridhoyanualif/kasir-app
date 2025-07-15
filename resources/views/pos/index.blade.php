<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-bold mb-4">Point of Sale</h2>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Scanner Webcam & Input Barcode -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Scan Barcode</h3>
                            <div id="qr-reader" class="w-full"></div>
                            <div class="flex mt-2">
                                <input type="text" id="barcodeInput"
                                    class="w-full px-3 py-2 border rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white bg-gray-700"
                                    placeholder="Scan Barcode">
                                <button type="button" id="add-barcode-btn"
                                    class="px-4 m py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700">Add</button>
                            </div>
                            <div class="flex mt-2">
                                <input type="number" id="memberPhoneInput"
                                    class="w-full px-3 py-2 border rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white bg-gray-700"
                                    placeholder="Member Phone">
                                <button id="search-member-btn" type="button"
                                    class="px-4 m py-2 bg-yellow-600 text-white rounded-r-md hover:bg-yellow-700">Search</button>
                            </div>
                            <table class="min-w-full border border-gray-300 dark:border-gray-600 mt-2"
                                id="member-table">
                                <thead class="bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">ID</th>
                                        <th class="border border-gray-300 px-4 py-2">Name</th>
                                        <th class="border border-gray-300 px-4 py-2">Point</th>
                                        <th class="border border-gray-300 px-4 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <!-- Cart Section -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Cart</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300 dark:border-gray-600" id="cart-table">
                                    <thead class="bg-gray-200 dark:bg-gray-700">
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2">Product</th>
                                            <th class="border border-gray-300 px-4 py-2">Price</th>
                                            <th class="border border-gray-300 px-4 py-2 w-[100px]">Qty</th>
                                            <th class="border border-gray-300 px-4 py-2">Total</th>
                                            <th class="border border-gray-300 px-4 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data cart akan masuk di sini -->
                                        <!-- Data cart berasal dari fungsi renderCart() di JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="text-lg font-semibold mt-4">Total: <span id="total-price"
                                    class="text-blue-300">0</span></h4>

                            <h4 class="text-lg font-semibold mt-1">Cut: <span id="cut"
                                    class="text-yellow-300">0</span></h4>

                            <div id="total-price-afterDiv" class="hidden">
                                <h4 class="text-lg font-semibold mt-1">Total After: <span id="total-price-after"
                                        class="text-purple-300">0</span></h4>
                            </div>

                            <div id="memberPointInput" class="mt-4 hidden">
                                <label for="point" class="block text-sm font-medium">Enter Member Point</label>
                                <input type="number" id="point" max="..." oninput="handleCashInput(this)"
                                    class="mt-1 w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white bg-gray-700">
                            </div>

                            <div class="mt-4">
                                <label for="cash" class="block text-sm font-medium">Enter Cash</label>
                                <input type="number" id="cash" oninput="updateChange()"
                                    class="mt-1 w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white bg-gray-700">
                            </div>



                            <h4 class="text-lg font-semibold mt-4">Change: <span id="change"
                                    class="text-green-500">0</span></h4>

                            <button id="checkout-btn"
                                class="mt-4 w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let cart = [];
        @if (session('pos_cart'))
            cart = Object.values(@json(session('pos_cart')));
            // Optionally clear session after loading
            fetch("{{ route('pos.clear.session') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            renderCart();
        @endif
        function onScanSuccess(decodedText) {
            fetch("{{ route('pos.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        barcode: decodedText
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        addToCart(data);
                    }
                })
                .catch(err => console.log(err));
        }

        function handleCashInput(el) {
            updateChange(); // update nilai kembalian
            el.value = Math.min(el.value, el.max); // batasi ke nilai maksimal

        }

        function parseRupiahToInt(str) {
            if (!str) return 0;
            return parseInt(
                str.replace(/Rp\.?\s?/gi, '') // hapus "Rp"
                .replace(/\./g, '') // hapus titik
                .replace(/,.*$/, '') // hapus koma/desimal
            ) || 0;
        }

        function updateChange() {
            const cashInput = document.getElementById('cash');
            const totalPriceEl = document.getElementById('total-price');
            const totalPriceAfterEl = document.getElementById('total-price-after');
            const changeEl = document.getElementById('change');

            if (!cashInput || !totalPriceEl || !changeEl) return;

            const cash = parseInt(cashInput.value) || 0;
            const totalPrice = parseRupiahToInt(totalPriceEl.innerText);
            const totalPriceAfter = totalPriceAfterEl ? parseRupiahToInt(totalPriceAfterEl.innerText) : 0;

            const usedPrice = (totalPriceAfter > 0) ? totalPriceAfter : totalPrice;
            const change = cash - usedPrice;

            changeEl.innerText = 'Rp. ' + change.toLocaleString('id-ID');
            changeEl.classList.toggle('text-red-500', change < 0);
            changeEl.classList.toggle('text-green-500', change >= 0);
        }



        function addToCart(product) {
            let existing = cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.quantity < product.stock) {
                    existing.quantity++;
                } else {
                    alert("Stock limit reached");
                }
            } else {
                cart.push({
                    ...product,
                    quantity: 1
                });
            }
            renderCart();
        }

        function renderCart() {
            let tbody = document.querySelector("#cart-table tbody");
            tbody.innerHTML = "";
            let totalPrice = 0;

            cart.forEach((item, index) => {
                let row = `<tr class="bg-white dark:bg-gray-800">
                <td class="border border-gray-300 px-4 py-2 text-center">${item.name}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">Rp. ${item.price.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace('.', ',')}</td>
                <td class="border border-gray-300 px-4 py-2 text-center"><input type="number" min="1" max="${item.stock}" value="${item.quantity}" onchange="updateQuantity(${index}, this.value)" class="mt-1 w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white bg-gray-700"></td>
                <td class="border border-gray-300 px-4 py-2 text-center">Rp. ${(item.quantity * item.price).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace('.', ',')}</td>
                <td class="border border-gray-300 px-4 py-2 text-center"><button class="px-2 py-1 bg-red-600 text-white rounded" onclick="removeItem(${index})">Remove</button></td>
            </tr>`;
                tbody.innerHTML += row;
                totalPrice += item.quantity * item.price;
            });

            document.getElementById("total-price").innerText = totalPrice.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).replace('IDR', '').trim();
        }

        function updateQuantity(index, qty) {
            cart[index].quantity = parseInt(qty);
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }




        // Member search by phone
        document.querySelector('#memberPhoneInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('#search-member-btn').click();
            }
        });
        document.querySelector('#search-member-btn').addEventListener('click', function() {
            let phone = document.getElementById('memberPhoneInput').value.trim();
            let totalPriceAfterDiv = document.getElementById('total-price-afterDiv');

            if (!phone) return;
            fetch('/members/search', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        telephone: phone
                    })
                })
                .then(res => res.json())
                .then(data => {
                    let tbody = document.querySelector("#member-table tbody");
                    let pointInputDiv = document.getElementById('memberPointInput');
                    let pointInput = document.getElementById('point');
                    let totalPriceAfterInput = document.getElementById('total-price-after');
                    tbody.innerHTML = "";

                    if (data.error) {
                        tbody.innerHTML =
                            `<tr><td colspan="4" class="text-center text-red-500">${data.error}</td></tr>`;
                        pointInputDiv.classList.add('hidden');
                        totalPriceAfterDiv.classList.add('hidden');

                        pointInput.removeAttribute('max');
                    } else {
                        tbody.innerHTML = `<tr class="bg-white dark:bg-gray-800">
            <td class="border border-gray-300 px-4 py-2 text-center">${data.id}</td>
            <td class="border border-gray-300 px-4 py-2 text-center">${data.name}</td>
            <td class="border border-gray-300 px-4 py-2 text-center">${data.point}</td>
            <td class="border border-gray-300 px-4 py-2 text-center">
                <span class="px-2 py-1 rounded text-white ${data.status === 'active' ? 'bg-green-600' : 'bg-red-600'}">
                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                </span>
            </td>
        </tr>`;

                        // Tampilkan input dan set max value
                        pointInputDiv.classList.remove('hidden');
                        totalPriceAfterDiv.classList.remove('hidden');
                        pointInput.max = data.point;
                        pointInput.value = ''; // reset nilai
                    }
                })


                .catch(err => alert('Error searching member'));
        });

        function updateCutAndTotalAfter() {
            const pointInput = document.getElementById('point');
            const totalPriceSpan = document.getElementById('total-price');
            const cutSpan = document.getElementById('cut');
            const totalAfterSpan = document.getElementById('total-price-after');
            const totalAfterDiv = document.getElementById('total-price-afterDiv');

            const point = parseInt(pointInput.value) || 0;
            const cut = point * 1000;
            cutSpan.textContent = cut.toLocaleString('id-ID');

            const total = parseRupiahToNumber(totalPriceSpan.textContent);
            const totalAfter = total - cut;

            totalAfterSpan.textContent = totalAfter
                .toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                })
                .replace('Rp', 'Rp.')
                .trim();

            totalAfterDiv.classList.remove('hidden');
        }

        function parseRupiahToNumber(rupiahText) {
            let clean = rupiahText.replace(/[^0-9,]/g, '')
                .replace(/\./g, '')
                .replace(',', '.');
            return parseFloat(clean) || 0;
        }

        document.getElementById('point').addEventListener('input', updateCutAndTotalAfter);




        document.getElementById("checkout-btn").addEventListener("click", function() {
            let totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            let cash = parseFloat(document.getElementById("cash").value) || 0;



            let memberId = document.querySelector("#member-table tbody tr td:first-child")?.innerText || null;
            let point = parseInt(document.querySelector("#member-table tbody tr td:nth-child(3)")?.innerText) || 0;
            let inputPoint = parseInt(document.getElementById("point").value) || 0;


            let point_after;
            if (!memberId) {
                point_after = 0;
            } else if (inputPoint > 0) {
                point_after = point - inputPoint;
            } else {
                if (totalPrice >= 1 && totalPrice <= 499999) {
                    point_after = point + 10;
                } else if (totalPrice >= 500000 && totalPrice <= 999999) {
                    point_after = point + 20;
                } else if (totalPrice >= 1000000 && totalPrice <= 5000000) {
                    point_after = point + 30;
                } else {
                    point_after = point; // Tidak berubah jika tidak masuk range
                }
            }

            let cut = inputPoint * 1000;
            let totalPriceAfter = totalPrice - cut;
            let totalYangDipakai = totalPriceAfter > 0 ? totalPriceAfter : totalPrice;

            if (cash < totalYangDipakai) {
                alert("Insufficient Cash");
                return;
            }


            fetch("{{ route('pos.checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        products: cart,
                        total_price: totalPrice,
                        cash: cash,
                        member_id: memberId,
                        point: point,
                        point_after: point_after,
                        cut: cut,
                        total_price_after: totalPriceAfter
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Tampilkan popup struk transaksi
                        showReceipt(data.transaction);
                    }
                })
                .catch(err => console.log(err));
        });

        function showReceipt(transaction) {
            function formatRupiah(number) {
                return 'Rp. ' + Number(number).toLocaleString('id-ID');
            }

            let receiptContent = `
    <div class="bg-white p-4 rounded-lg shadow text-gray-900" id="receipt-content">
        <div class="mx-auto h-20 w-20 relative" id="logo-container">
                            <span class="absolute inset-0 rounded-full border-2 border-gray-800"></span>
                            <span class="absolute inset-2 rounded-full border-2 border-gray-400"></span>
                            <img src="{{ asset('images/Kasir.png') }}" alt="logo"
                                class="relative h-full w-full rounded-full object-cover p-2">
                        </div>
    <h2 class="text-lg font-semibold my-2 text-center">Transaction Receipt</h2>
    <hr class="mb-2 border-gray-900">
    <p class="block text-sm font-medium"><strong>Cashier ID:</strong> ${transaction.cashier_id} - ${transaction.cashier_name}</p>
    <p class="block text-sm font-medium"><strong>Invoice:</strong> ${transaction.invoice}</p>
    <p class="block text-sm font-medium"><strong>Date:</strong> ${transaction.transaction_date}</p>

    ${transaction.member_id ? `
                            <hr class="my-2 border-gray-900">
                            <p class="block text-sm font-medium"><strong>Member ID:</strong> ${transaction.member_id}</p>
                            <p class="block text-sm font-medium"><strong>Member Name:</strong> ${transaction.member_name}</p>
                            <p class="block text-sm font-medium"><strong>Point Before:</strong> ${transaction.point}</p>
                            <p class="block text-sm font-medium"><strong>Point After:</strong> ${transaction.point_after}</p>
                        ` : ''}

    <hr class="my-2 border-gray-900">
    <h3 class="text-lg font-semibold">Items:</h3>
    <table class="w-full border-collapse border border-gray-900 mt-2">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-900 px-2 py-1 text-left">Product</th>
                <th class="border border-gray-900 px-2 py-1 text-center">Qty</th>
                <th class="border border-gray-900 px-2 py-1 text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            ${transaction.items.map(item => `
                                    <tr>
                                        <td class="border border-gray-900 px-2 py-1">${item.name}</td>
                                        <td class="border border-gray-900 px-2 py-1 text-center">${item.quantity}</td>
                                        <td class="border border-gray-900 px-2 py-1 text-right">${formatRupiah(item.price)}</td>
                                    </tr>
                                `).join('')}
        </tbody>
    </table>

    <hr class="my-2 border-gray-900">
    <p><strong>Total Price:</strong> <span class="text-blue-800">${formatRupiah(transaction.total_price)}</span></p>
    ${transaction.cut >= 0 ? `<p><strong>Cut (Point):</strong> <span class="text-red-600">- ${formatRupiah(transaction.cut)}</span></p>` : ''}
    ${transaction.total_price_after > 0 ? `<p><strong>Total After Cut:</strong> <span class="text-blue-850">${formatRupiah(transaction.total_price_after)}</span></p>` : ''}
    <p><strong>Cash:</strong> <span class="text-green-800">${formatRupiah(transaction.cash)}</span></p>
    <p><strong>Change:</strong> <span class="text-yellow-600">${formatRupiah(transaction.change)}</span></p>
</div>


    <button id="exclude-pdf-close" onclick="closeReceipt()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded w-full">Close</button>
    <button id="exclude-pdf-button" onclick="downloadReceiptPDF('${transaction.invoice}')" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded w-full">
    PDF Receipt
</button>
`;



            let receiptDiv = document.createElement("div");
            receiptDiv.id = "receipt-popup";
            receiptDiv.className = "fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center";
            receiptDiv.innerHTML =
                `<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">${receiptContent}</div>`;

            document.body.appendChild(receiptDiv);
        }




        function closeReceipt() {
            window.location.reload();
        }




        // Add product by barcode input
        document.querySelector('#barcodeInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('#add-barcode-btn').click();
            }
        });
        document.querySelector('#add-barcode-btn').addEventListener('click', function() {
            let barcode = document.getElementById('barcodeInput').value.trim();
            if (!barcode) return;
            fetch("{{ route('pos.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        barcode: barcode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        addToCart(data);
                        document.getElementById('barcodeInput').value = '';
                    }
                })
                .catch(err => alert('Error adding product'));
        });








        new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        }).render(onScanSuccess);
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadReceiptPDF(invoice) {
            const content = document.getElementById("receipt-content");

            // Default nama file kalau invoice kosong
            const filename = (invoice ? invoice : "Receipt") + "_Receipt.pdf";

            const closeBtn = document.getElementById("exclude-pdf-close");
            const pdfBtn = document.getElementById("exclude-pdf-button");

            if (closeBtn) closeBtn.style.display = "none";
            if (pdfBtn) pdfBtn.style.display = "none";

            const clone = content.cloneNode(true);
            const tempContainer = document.createElement("div");
            tempContainer.style.position = "fixed";
            tempContainer.style.left = "-9999px";
            tempContainer.appendChild(clone);
            document.body.appendChild(tempContainer);

            html2pdf().set({
                    margin: 0.5,
                    filename: filename,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                })
                .from(clone)
                .save()
                .then(() => {
                    document.body.removeChild(tempContainer);
                    if (closeBtn) closeBtn.style.display = "block";
                    if (pdfBtn) pdfBtn.style.display = "block";
                });
        }
    </script>







</x-app-layout>
