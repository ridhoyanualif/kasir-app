<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;






// use App\Http\Controllers\TransactionController;







use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::post('/members/search', [MemberController::class, 'search']);
});





require __DIR__ . '/auth.php';


Route::middleware(['auth', 'cashierMiddleware'])->group(function () {
    Route::get('/dashboard', [CashierController::class, 'index'])->name('dashboard');

    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [POSController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');

    Route::post('/pos/clear-session', function () {
        session()->forget('pos_cart');
        return response()->noContent();
    })->name('pos.clear.session');
    Route::post('/pos/from-cart', [\App\Http\Controllers\POSController::class, 'fromCart'])->name('pos.from.cart');

    Route::post('/generate-pdf', [ReceiptController::class, 'generatePdf'])->name('generate.pdf');

    Route::get('/dashboard', [CartController::class, 'index'])->name('dashboard');
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
    // Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');
    Route::post('cart/decrease', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
    Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');
});



Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('/admin/selling-report/pdf', [UserController::class, 'generatePdf'])->name('admin.generate-pdf');

    Route::resource('categories', CategoryController::class);

    // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::resource('products', ProductController::class);
    // Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    Route::get('/check-barcode', [ProductController::class, 'checkBarcode'])->name('barcode.check');




    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');

    // Add routes for editing and deleting cashiers
    Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});
