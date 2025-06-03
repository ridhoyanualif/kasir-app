<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
{
    $request->merge(['name' => strtolower(trim($request->name))]);

    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name'
    ]);

    Category::create([
        'name' => $request->name
    ]);

    return redirect()->route('categories.index')->with('success', 'Category added successfully!');
}

public function edit($id)
{
    $category = Category::where('id_category', $id)->firstOrFail();
    return view('categories.edit', compact('category'));
}

    // Memperbarui kategori
    public function update(Request $request, $id)
    {
        $request->merge(['name' => strtolower(trim($request->name))]);

$request->validate([
    'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id_category'
]);


        // Temukan kategori dan update
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        try {
            Category::where('id_category', $id)->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation (MySQL error code 1451)
            if ($e->getCode() == '23000') {
                return redirect()->route('categories.index')->with('error', 'Cannot delete category: it is still used by products.');
            }
            throw $e;
        }
    }
}
