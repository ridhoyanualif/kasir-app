<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'expired_date' => 'nullable|date',
            'stock' => 'required|integer',
            'modal' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'fid_category' => 'required|exists:categories,id_category',
            'description' => 'nullable|string',
        ]);

        $profit = $request->selling_price - $request->modal;
        $path = $request->file('photo')?->store('product_photos', 'public');

        Product::create([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'photo' => $path,
            'expired_date' => $request->expired_date,
            'stock' => $request->stock,
            'modal' => $request->modal,
            'selling_price' => $request->selling_price,
            'profit' => $profit,
            'fid_category' => $request->fid_category,
            'description' => $request->description,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'expired_date' => 'nullable|date',
            'stock' => 'required|integer',
            'modal' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'fid_category' => 'required|exists:categories,id_category',
            'description' => 'nullable|string',
        ]);

        $profit = $request->selling_price - $request->modal;

        $product = Product::findOrFail($id);

        // Prepare data except photo
        $data = [
            'name' => $request->name,
            'expired_date' => $request->expired_date,
            'stock' => $request->stock,
            'modal' => $request->modal,
            'selling_price' => $request->selling_price,
            'profit' => $profit,
            'fid_category' => $request->fid_category,
            'description' => $request->description,
        ];

        // Handle new photo upload
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store('product_photos', 'public');
        }

        // Handle remove photo
        if ($request->has('remove_photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = null;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }


public function checkBarcode(Request $request)
{
    $exists = \App\Models\Product::where('barcode', $request->barcode)->exists();
    return response()->json(['exists' => $exists]);
}



    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

}
