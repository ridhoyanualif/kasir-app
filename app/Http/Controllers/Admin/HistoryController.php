<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Spatie\Browsershot\Browsershot;

class HistoryController extends Controller
{
    //
    public function index(Request $request) {
        //
        $transactions = Transaction::latest('id')->get();

        return view('admin.history.index', compact('transactions'));
    }

    public function show($id) {
        $transaction = Transaction::findOrFail($id);
        $items = TransactionDetail::where('transaction_id', $id)->get();
        return view('admin.history.show', compact('transaction', 'items'));
    }

    public function destroy($id) {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('admin.history.index')->with('success', 'Transaction deleted successfully.');
    }

    public function search(Request $request) {
        $query = $request->input('query');
        $transactions = Transaction::where('invoice', 'like', "%{$query}%")
            ->orWhere('customer_name', 'like', "%{$query}%")
            ->latest('id')
            ->get();

        return view('admin.history.index', compact('transactions'));
    }
}
