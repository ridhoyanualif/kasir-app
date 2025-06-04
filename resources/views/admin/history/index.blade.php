<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction List') }}
        </h2>
    </x-slot>


    <div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-semibold mb-4">Transactions</h3>

                <div class="overflow-x-auto rounded">
                    <table class="min-w-full text-sm text-left border border-white">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 uppercase tracking-wider text-xs border-b border-white">
                            <tr>
                                <th class="px-4 py-3 border border-white">ID</th>
                                <th class="px-4 py-3 border border-white">Invoice</th>
                                <th class="px-4 py-3 border border-white">DateTime</th>
                                <th class="px-4 py-3 border border-white">Cashier</th>
                                <th class="px-4 py-3 border border-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-white">
                                    <td class="px-4 py-2 border border-white">{{ $transaction->id }}</td>
                                    <td class="px-4 py-2 border border-white">{{ $transaction->invoice ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border border-white">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-2 border border-white">{{ $transaction->user->name }}</td>
                                    <td class="px-4 py-2 border border-white flex gap-2">
                                        <a href="{{ route('history.show', $transaction->id) }}"
                                           class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium">
                                            Show
                                        </a>
                                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
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


</x-app-layout>
