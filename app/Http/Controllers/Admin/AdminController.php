<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Member;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //

    public function index(Request $request)
{
    $month = $request->query('month');
    $week = $request->query('week');

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

    $salesPerDay = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $dates = [];
    $totals = [];

    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
        $formatted = $date->format('Y-m-d');
        $dates[] = $formatted;
        $match = $salesPerDay->firstWhere('date', $formatted);
        $totals[] = $match ? $match->total : 0;
    }

    // Total Penjualan (Laba Kotor)
    $grossProfit = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->sum('total_price');

    // Ambil ID transaksi dalam range
    $transactionIds = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->pluck('id');

    // Total Modal dari transaksi detail
    $totalCost = TransactionDetail::whereIn('transaction_id', $transactionIds)
        ->join('products', 'transaction_details.product_id', '=', 'products.id_product')
        ->selectRaw('SUM(transaction_details.quantity * products.modal) as total_modal')
        ->value('total_modal');

    // Keuntungan = total - total modal
    $netProfit = $grossProfit - $totalCost;
    $profitLevel = ($netProfit / $totalCost) * 100;

    if ($profitLevel < 10) {
    $profitLabel = '= Sangat Rendah';
    $profitColor = 'text-red-600 dark:text-red-400';
} elseif ($profitLevel <= 30) {
    $profitLabel = '= Rendah';
    $profitColor = 'text-yellow-600 dark:text-yellow-400';
} elseif ($profitLevel <= 60) {
    $profitLabel = '= Menengah';
    $profitColor = 'text-blue-600 dark:text-blue-400';
} elseif ($profitLevel <= 100) {
    $profitLabel = '= Tinggi';
    $profitColor = 'text-green-600 dark:text-green-400';
} else {
    $profitLabel = '= Sangat Tinggi';
    $profitColor = 'text-green-600 dark:text-green-400';
}

    return view('admin.dashboard', [
        'totalProducts' => Product::count(),
        'totalMembers' => Member::count(),
        'totalCategories' => Category::count(),
        'totalCashiers' => User::where('role', 'cashier')->count(),
        'startDate' => $startDate,
        'endDate' => $endDate,
        'grossProfit' => $grossProfit,
        'netProfit' => $netProfit,
        'totalCost' => $totalCost,
        'profitLevel' => $profitLevel,
        'profitLabel' => $profitLabel,
        'profitColor' => $profitColor,
        // data untuk grafik penjualan
        'dates' => $dates ?? [],
        'totals' => $totals ?? [],
        'month' => $month,
        'week' => $week,
    ]);
}



    public function edit($id)
    {
        $user = User::findOrFail($id); // Find the cashier by ID
        return view('admin.users.edit', compact('user')); // Return the edit view
    }



    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profile_photos', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        if ($request->has('remove_photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Cashier updated!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Cashier deleted successfully!');
    }
}
