<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Discount;

class CartController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $products = Product::where('stock', '>', 0)->where('expired_date', '>', now())->orWhereNull('expired_date')->get();
        return view('dashboard', compact('products'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart()
    {
        return view('cart');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // Check if there is enough stock
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product out of stock!');
        }

        // Get the current cart from the session
        $cart = session()->get('cart', []);

        // If the product is already in the cart
        if (isset($cart[$id])) {
            // Check if the added quantity exceeds the available stock
            if ($cart[$id]['quantity'] < $product->stock) {
                // Increment quantity only if stock is available
                $cart[$id]['quantity']++;
            } else {
                return redirect()->back()->with('error', 'Not enough stock available!');
            }
        } else {
            // If the product is not in the cart, add it with quantity 1
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->selling_price,
                "image" => asset('storage/' . $product->photo),
            ];
        }

        // Save the cart back to the session
        session()->put('cart', $cart);
        session()->flash('open_cart', true);

        // Success message
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request) {}

    public function decreaseQuantity(Request $request)
    {
        $id = $request->id;
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                session()->put('cart', $cart);
            } else {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }
        // Set flash session to open cart
        session()->flash('open_cart', true);
        return redirect()->back()->with('success', 'Cart updated!');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('open_cart', true);
            return redirect()->back()->with('success', 'Product removed successfully');
        }

        return redirect()->back()->with('error', 'Product not found in cart');
    }
}
