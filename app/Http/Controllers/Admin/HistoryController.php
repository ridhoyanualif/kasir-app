<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;

class HistoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $month = request('month', now()->format('Y-m'));
        $week = request('week');

        $query = Transaction::query();

        if (!$month && !$week) {
            $month = now()->format('Y-m');
        }

        if ($week) {
            $startDate = Carbon::parse($week)->startOfWeek(Carbon::MONDAY);
            $endDate = Carbon::parse($week)->endOfWeek(Carbon::SUNDAY);
        } else {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $transactions = $query->latest('id')->get();

        $totalTransactions = $transactions->count();
        $totalRevenue = 0;
        $totalModal = 0;
        $totalProfit = 0;

        foreach ($transactions as $transaction) {
            $totalCost = TransactionDetail::where('transaction_id', $transaction->id)
                ->join('products', 'transaction_details.product_id', '=', 'products.id_product')
                ->selectRaw('SUM(transaction_details.quantity * products.modal) as total_modal')
                ->value('total_modal');

            $transaction->total_modal = $totalCost ?? 0;
            $transaction->net_profit = $transaction->total_price_after - $transaction->total_modal;

            $totalRevenue += $transaction->total_price_after;
            $totalModal += $transaction->total_modal;
            $totalProfit += $transaction->net_profit;
        }

        return view('admin.history.index', [
            'transactions' => $transactions,
            'month' => $month,
            'week' => $week,
            'totalRevenue' => $totalRevenue,
            'totalModal' => $totalModal,
            'totalProfit' => $totalProfit,
            'totalTransactions' => $totalTransactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        $items = TransactionDetail::where('transaction_id', $id)->get();
        return view('admin.history.show', compact('transaction', 'items'));
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('history.index')->with('success', 'Transaction deleted successfully.');
    }
}
