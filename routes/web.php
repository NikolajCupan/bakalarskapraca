<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminPurchaseController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * MainController
 */

// Welcome page
Route::get('/', [MainController::class, 'index']);

// Contact page
Route::get('/contact', [MainController::class, 'contact']);

// About us page
Route::get('/about', [MainController::class, 'about']);

// Terms and conditions page
Route::get('/terms', [MainController::class, 'terms']);

// Page for testing html
Route::get('/test', [MainController::class, 'test']);



/*
 * UserController
 */

// Register page
Route::get('/register', [UserController::class, 'register'])
    ->middleware('guest');

// Login page
Route::get('/login', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('login');

// Select edit type page
Route::get('/user/select', [UserController::class, 'editSelect'])
    ->middleware('auth');

// Edit profile page
Route::get('/user/edit', [UserController::class, 'editProfile'])
    ->middleware('auth');

// Edit photo page
Route::get('/user/photo', [UserController::class, 'editPhoto'])
    ->middleware('auth');

// Edit password page
Route::get('/user/password', [UserController::class, 'editPassword'])
    ->middleware('auth');

// Delete account page
Route::get('/user/delete', [UserController::class, 'editDelete'])
    ->middleware('auth');


// Register user
Route::post('/register', [UserController::class, 'store']);

// Login user
Route::post('/login', [UserController::class, 'authenticate']);

// Logout
Route::post('/logout', [UserController::class, 'logout']);

// Edit profile
Route::post('/user/edit', [UserController::class, 'updateProfile']);

// Edit photo
Route::post('/user/photo', [UserController::class, 'updatePhoto']);

// Edit password
Route::post('/user/password', [UserController::class, 'updatePassword']);

// Delete account
Route::post('/user/delete', [UserController::class, 'updateDelete']);


// AJAX call to get current user's values from database
Route::get('/getPreviousValues', [UserController::class, 'getPreviousValues'])
    ->middleware('ajax');



/*
 * UserShopController
 */

// User's purchase history page
Route::get('/user/purchaseHistory', [UserShopController::class, 'purchaseHistory'])
    ->middleware('auth');

// Purchase detail page
Route::get('/user/purchaseDetail/{id_purchase}', [UserShopController::class, 'purchaseDetail'])
    ->middleware('auth');

// Confirm purchase delivery (user can confirm purchase delivery if it has status delivered)
Route::post('/user/purchase/confirm', [UserShopController::class, 'confirmPurchaseDelivery'])
    ->middleware('auth');


// Current basket page
Route::get('/user/basket/show', [UserShopController::class, 'basket'])
    ->middleware('auth');

// Add shop product to logged user's basket
Route::post('/user/addToBasket', [UserShopController::class, 'addToBasket'])
    ->middleware('auth');

// AJAX call to edit basket product quantity
Route::post('/user/editBasketProductQuantity', [UserShopController::class, 'editBasketProductQuantity'])
    ->middleware('ajax');

// Removes product from user's basket
Route::post('/user/destroyBasketProduct', [UserShopController::class, 'destroyBasketProduct'])
    ->middleware('auth');

// AJAX call to get total purchase price
Route::get('/user/getTotalPurchasePrice', [UserShopController::class, 'getTotalPurchasePrice'])
    ->middleware('ajax');

// AJAX call to get information if basket is orderable
Route::get('/user/isBasketOrderable', [UserShopController::class, 'isBasketOrderable'])
    ->middleware('ajax');

// Confirm purchase page
Route::get('/user/basket/confirm', [UserShopController::class, 'confirmPurchase'])
    ->middleware('auth');

// Confirmed purchase page
Route::get('/user/basket/confirmed', [UserShopController::class, 'confirmedPurchase'])
    ->middleware('auth');

// Validate information (address, phone number) and make the purchase
Route::post('/user/purchase/makePurchase', [UserShopController::class, 'makePurchase'])
    ->middleware('auth');

// Create review of product from user
Route::post('/user/createReview', [UserShopController::class, 'storeReview'])
    ->middleware('auth');

// Delete review of product from user
Route::post('/user/destroyReview', [UserShopController::class, 'destroyReview'])
    ->middleware('auth');

// AJAX call to get edit user's review of product
Route::post('/user/editReview', [UserShopController::class, 'editReview'])
    ->middleware('ajax');



/*
 * ShopController
 */

// Show products according to what user searched page
// NOTE: Must be placed above other routes from ShopController,
//       otherwise it will not be called
Route::get('/shop/product/search', [ShopController::class, 'showSearchedProducts']);

// Show products from category page
Route::get('/shop/{category}', [ShopController::class, 'showCategory']);

// Show single product page
Route::get('/shop/product/{id_product}', [ShopController::class, 'showProduct']);



/*
 * AdminController
 */

// Admin page
Route::get('/admin', [AdminController::class, 'admin']);

// Users management page
Route::get('/admin/user', [AdminController::class, 'user']);

// Products management page
Route::get('/admin/product', [AdminController::class, 'product']);

// Purchase management page
Route::get('/admin/purchase', [AdminController::class, 'purchase']);



/*
 * AdminUserController
 */

// Table with users with some web role page
Route::post('/admin/user/users', [AdminUserController::class, 'showUsers']);

// Single user information page
Route::get('/admin/user/show/{id_user}', [AdminUserController::class, 'showUser']);

// Modify user's roles
Route::post('/admin/user/modifyUserRoles', [AdminUserController::class, 'modifyUserRoles']);



/*
 * AdminPurchaseController
 */

// Table with purchases with some purchase status page
Route::post('/admin/purchase/purchases', [AdminPurchaseController::class, 'showPurchases']);

// Single purchase information page
Route::get('/admin/purchase/show/{id_purchase}', [AdminPurchaseController::class, 'showPurchase']);

// Cancel purchase
Route::post('/admin/purchase/cancelPurchase', [AdminPurchaseController::class, 'cancelPurchase']);

// Modify status of purchase (cannot be modified to cancelled status this way)
Route::post('/admin/purchase/modifyPurchaseStatus', [AdminPurchaseController::class, 'modifyPurchaseStatus']);

// Modify payment date of purchase (cancelled purchase cannot be modified)
Route::post('/admin/purchase/modifyPurchasePaymentDate', [AdminPurchaseController::class, 'modifyPurchasePaymentDate']);

// Reclaim product from a purchase
Route::post('/admin/purchase/productReclaim', [AdminPurchaseController::class, 'productReclaim']);

// Deletes purchase from database
Route::post('/admin/purchase/destroyPurchase', [AdminPurchaseController::class, 'destroyPurchase']);



/*
 * AdminProductController
 */

// Warehouse active products page
Route::get('/admin/product/warehouse/active', [AdminProductController::class, 'warehouseActive']);

// Warehouse inactive products page
Route::get('/admin/product/warehouse/inactive', [AdminProductController::class, 'warehouseInactive']);

// Warehouse new product page
Route::get('/admin/product/warehouse/create', [AdminProductController::class, 'warehouseCreate']);

// Edit warehouse product page
Route::get('/admin/product/warehouse/edit/{id_warehouse_product}', [AdminProductController::class, 'warehouseEdit']);

// Store new warehouse product
Route::post('/admin/product/warehouse/create', [AdminProductController::class, 'warehouseStore']);

// Update warehouse product
Route::post('/admin/product/warehouse/update', [AdminProductController::class, 'warehouseUpdate']);

// Delete warehouse product
Route::post('/admin/product/warehouse/destroy', [AdminProductController::class, 'warehouseDestroy']);


// Shop active products page
Route::get('/admin/product/shop/active', [AdminProductController::class, 'shopActive']);

// Shop inactive products page
Route::get('/admin/product/shop/inactive', [AdminProductController::class, 'shopInactive']);

// Shop salable products page
Route::get('/admin/product/shop/salable', [AdminProductController::class, 'shopSalable']);

// Shop new product page
Route::get('/admin/product/shop/create/{id_warehouse_product}', [AdminProductController::class, 'shopCreate']);

// Show single shop product page
Route::get('/admin/product/shop/show/{id_product}', [AdminProductController::class, 'shopShow']);

// Show image of shop product page
Route::get('/admin/product/shop/image/{id_image}', [AdminProductController::class, 'shopImage']);

// Store new shop product
Route::post('/admin/product/shop/create', [AdminProductController::class, 'shopStore']);

// End sale of shop product
Route::post('/admin/product/shop/endSale', [AdminProductController::class, 'shopEndSale']);

// Update shop product
Route::post('/admin/product/shop/update', [AdminProductController::class, 'shopUpdate']);



/*
 * PdfController
 */
Route::post('/pdf/purchase', [PdfController::class, 'pdfPurchase']);
