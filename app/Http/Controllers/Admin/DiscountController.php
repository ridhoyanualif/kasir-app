<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    //
    public function index()
    {
        $discounts = Discount::select(['id', 'name', 'description', 'cut', 'start_datetime', 'end_datetime'])->get();

        $dropdowns = Product::whereNull('fid_discount')
            ->get(['id_product', 'name', 'selling_price', 'photo']);
        $dropdowns->transform(function ($item) {
            $item->photo = $item->photo ? asset('storage/' . $item->photo) : '-';
            $item->selling_price = number_format($item->selling_price, 0, ',', '.');
            return $item;
        });


        return view('admin.discounts.index', compact('dropdowns', 'discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'description' => 'nullable|string|max:500',
            'cut' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fid_product' => 'required|exists:products,id_product',
        ]);

        // Create the discount
        $discount = Discount::create([
            'name' => $request->name,
            'description' => $request->description,
            'cut' => $request->cut,
            'start_datetime' => $request->start_date,
            'end_datetime' => $request->end_date,
        ]);

        // Update the product
        $product = Product::findOrFail($request->fid_product);

        $product->update([
            'fid_discount' => $discount->id,
            'selling_price_before' => $product->selling_price,
            'selling_price' => $product->selling_price - ($request->cut / 100 * $product->selling_price),
        ]);

        return redirect()->route('discounts.index')->with('Success', 'Discount added successfully!');
    }

    public function edit()
    {
        return view('admin.discounts.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'description' => 'nullable|string|max:500',
            'cut' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update([
            'name' => $request->name,
            'description' => $request->description,
            'cut' => $request->cut,
            'start_datetime' => $request->start_date,
            'end_datetime' => $request->end_date,
        ]);

        return redirect()->route('discounts.index')->with('Success', 'Discount updated successfully!');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        // Reset the products that were using this discount
        Product::where('fid_discount', $id)->update([
            'fid_discount' => null,
            'selling_price' => DB::statement("
    UPDATE products
    SET selling_price = selling_price_before,
        selling_price_before = NULL,
        fid_discount = NULL WHERE fid_discount = {$id}"),
            'selling_price_before' => null,
        ]);

        return redirect()->route('discounts.index')->with('Success', 'Discount deleted successfully!');
    }
}
