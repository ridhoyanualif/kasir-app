<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Discount;

class DiscountController extends Controller
{
    //
    public function index()
    {
        $dropdowns = Product::whereNull('fid_discount')
            ->get(['id_product', 'name', 'selling_price', 'photo']);
        $dropdowns->transform(function ($item) {
            $item->photo = $item->photo ? asset('storage/' . $item->photo) : '-';
            $item->selling_price = number_format($item->selling_price, 0, ',', '.');
            return $item;
        });


        return view('admin.discounts.index', compact('dropdowns'));
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

        return redirect()->route('admin.discounts.index')->with('Success', 'Discount added successfully!');
    }
}
