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
            ->where('stock', '>', 0)
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
            'cut' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
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

        $newSellingPrice = $product->selling_price - ($request->cut / 100 * $product->selling_price);

        $product->update([
            'fid_discount' => $discount->id,
            'selling_price_before' => $product->selling_price,
            'selling_price' => $newSellingPrice,
            'profit' => $newSellingPrice - $product->modal
        ]);

        return redirect()->route('discounts.index')->with('Success', 'Discount added successfully!');
    }

    public function edit($id)
    {
        $discounts = Discount::findOrFail($id);
        $discounts = Discount::select(['id', 'name', 'description', 'cut', 'start_datetime', 'end_datetime'])->where('id', $id)->firstOrFail();

        $selectedProduct = Product::where('fid_discount', $id)->first();
        $selectedProduct->photo = $selectedProduct->photo ? asset('storage/' . $selectedProduct->photo) : '-';
        $selectedProduct->selling_price = number_format($selectedProduct->selling_price, 0, ',', '.');

        $dropdowns = Product::select(['id_product', 'name', 'selling_price', 'photo'])
            ->get();
        $dropdowns->transform(function ($item) {
            $item->photo = $item->photo ? asset('storage/' . $item->photo) : '-';
            $item->selling_price = number_format($item->selling_price, 0, ',', '.');
            return $item;
        });

        return view('admin.discounts.edit', compact('dropdowns', 'discounts', 'selectedProduct'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'description' => 'nullable|string|max:500',
            'cut' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fid_product' => 'required|exists:products,id_product',
        ]);

        $product = Product::select(['id_product', 'selling_price', 'selling_price_before', 'profit', 'fid_discount'])->get();

        if ($product = Product::where('fid_discount', $id)
            ->where('id_product', $request->fid_product)->first()
        ) {
            $discount = Discount::findOrFail($id);
            $discount->update([
                'name' => $request->name,
                'description' => $request->description,
                'cut' => $request->cut,
                'start_datetime' => $request->start_date,
                'end_datetime' => $request->end_date,
            ]);

            $newSellingPrice = $product->selling_price_before - ($request->cut / 100 * $product->selling_price_before);

            $product->update([
                'selling_price' => $newSellingPrice,
                'profit' => $newSellingPrice - $product->modal
            ]);
        } else {
            $discount = Discount::findOrFail($id);
            $discount->update([
                'name' => $request->name,
                'description' => $request->description,
                'cut' => $request->cut,
                'start_datetime' => $request->start_date,
                'end_datetime' => $request->end_date,
            ]);

            $product = Product::where('fid_discount', $id)->firstOrFail();
            Product::where('fid_discount', $id)->update([
                'fid_discount' => null,
                'selling_price' => $product->selling_price_before,
                'selling_price_before' => null,
                'profit' => $product->selling_price_before - $product->modal
            ]);

            $product = Product::findOrFail($request->fid_product);
            $newSellingPrice = $product->selling_price - ($request->cut / 100 * $product->selling_price);
            $product->update([
                'fid_discount' => $discount->id,
                'selling_price_before' => $product->selling_price,
                'selling_price' => $newSellingPrice,
                'profit' => $newSellingPrice - $product->modal
            ]);
        }

        return redirect()->route('discounts.index')->with('Success', 'Discount updated successfully!');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        // Reset the products that were using this discount
        $product = Product::where('fid_discount', $id)->firstOrFail();
        Product::where('fid_discount', $id)->update([
            'fid_discount' => null,
            'selling_price' => $product->selling_price_before,
            'selling_price_before' => null,
            'profit' => $product->selling_price_before - $product->modal
        ]);

        $discount->delete();

        return redirect()->route('discounts.index')->with('Success', 'Discount deleted successfully!');
    }
}
