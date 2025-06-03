<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Validator;

class POSController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function addToCart(Request $request)
    {
        $barcode = $request->barcode;
        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id_product,
            'name' => $product->name,
            'stock' => $product->stock,
            'price' => $product->selling_price,
        ]);
    }


    public function fromCart(Request $request)
    {
        // Simpan produk ke session sementara untuk POS
        session(['pos_cart' => $request->products]);
        session()->forget('cart');
        return redirect()->route('pos.index');
    }

    public function checkout(Request $request)
    {

        $minCash = ($request->total_price_after > 0)
            ? $request->total_price_after
            : $request->total_price;

        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'total_price' => 'required|numeric',
            'cash' => 'required|numeric|min:' . $minCash,
        ], [
            'cash.min' => 'Cash must be at least Rp ' . number_format($minCash, 0, ',', '.'),
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        do {
            $invoice = 'INV-' . now()->format('Ymd') . '-' . mt_rand(1000, 9999);
        } while (Transaction::where('invoice', $invoice)->exists());


        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice' => $invoice,
                'fid_member' => $request->member_id ?? null,
                'total_price' => $request->total_price,
                'cash' => $request->cash,
                'change' => ($request->total_price_after ?? 0) > 0
                    ? $request->cash - $request->total_price_after
                    : $request->cash - $request->total_price,
                'transaction_date' => Carbon::now(),
                'point' => $request->point ?? 0,
                'point_after' => $request->point_after ?? 0,
                'cut' => $request->cut ?? 0,
                'total_price_after' => $request->total_price_after ?? 0,
            ]);


            if ($request->member_id) {
                Member::where('id_member', $request->member_id)->update([
                    'point' => $request->point_after ?? 0,
                ]);
            }


            $items = [];

            foreach ($request->products as $item) {
                $product = Product::find($item['id']);

                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['error' => 'Product stock is insufficient'], 400);
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id_product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);

                $items[] = [
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaction successful',
                'transaction' => [
                    'invoice' => $transaction->invoice,
                    'cashier_id' => $transaction->user_id,
                    'cashier_name' => Auth::user()->name,
                    'transaction_date' => $transaction->transaction_date->format('Y-m-d H:i:s'),
                    'total_price' => $transaction->total_price,
                    'cash' => $transaction->cash,
                    'change' => $transaction->change,
                    'items' => $items,
                    'member_id' => $transaction->fid_member,
                    'point' => $transaction->point,
                    'point_after' => $transaction->point_after,
                    'cut' => $transaction->cut,
                    'total_price_after' => $transaction->total_price_after,
                    'member_name' => $transaction->member ? $transaction->member->name : null,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }
}
