<?php
Route::redirect('/dashboard', '/admin/dashboard');
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Route;


// ============================================================
// PUBLIC FRONT-END ROUTES
// ============================================================
Route::get('/admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/produit/{id}', [FrontController::class, 'show'])->name('produit.details');
Route::get('/recherche', [FrontController::class, 'search'])->name('search');

// Cart (public)
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/modifier', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/retirer', [CartController::class, 'remove'])->name('cart.remove');

// Coupon
Route::post('/panier/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

// Order Tracking (public)
Route::get('/suivi-commande', [\App\Http\Controllers\OrderTrackingController::class, 'index'])->name('tracking.index');
Route::post('/suivi-commande', [\App\Http\Controllers\OrderTrackingController::class, 'track'])->name('tracking.track');

// ============================================================
// AUTH-REQUIRED ROUTES
// ============================================================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::get('/favoris', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/favoris/{id}', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Reviews
    Route::post('/produit/{id}/avis', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');

    // Checkout
    Route::get('/commande/livraison', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/commande/livraison', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Payment
    Route::get('/commande/paiement/{order}', [PaymentController::class, 'show'])->name('payment.page');
    Route::post('/commande/paiement/{order}', [PaymentController::class, 'process'])->name('payment.process');

    // Order confirmation
    Route::get('/commande/confirmation/{order}', [PaymentController::class, 'confirmation'])->name('order.confirmation');
});

// ============================================================
// ADMIN ROUTES
// ============================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/commandes', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/commandes/{id}', [AdminOrderController::class, 'show'])->name('order.show');
    Route::post('/commandes/{id}/statut', [AdminOrderController::class, 'updateStatus'])->name('order.updateStatus');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories');
    Route::get('/categories/creer', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/modifier', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
});

// Products CRUD (admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);
});
require __DIR__.'/auth.php';


