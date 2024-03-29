<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Helpers\Helper;
use App\Helpers\RecordCreatorHelper;
use App\Helpers\ValidationHelper;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\City;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseStatus;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserShopController extends Controller
{
    // Basket page
    public function basket()
    {
        $user = Auth::user();
        $basket = $user->getCurrentBasket();

        return view('user.shop.basket', [
            'basket' => $basket
        ]);
    }

    // User's purchase history page
    public function purchaseHistory()
    {
        $user = Auth::user();
        $userPurchases = $user->getPurchases();
        // user, basket and imagePath is sent to view using AppServiceProvider
        return view('user.purchase.purchaseHistory', [
            'userPurchases' => $userPurchases
        ]);
    }

    // Purchase detail page
    public function purchaseDetail($id_purchase)
    {
        $purchase = Purchase::where('id_purchase', '=', $id_purchase)
                            ->first();

        // If user does not own purchase, redirect to main page
        if (!$purchase->isOwnedByUser(Auth::user()))
        {
            return redirect('/');
        }

        // Getting here means user owns the purchase, thus he can see it
        // user, basket and imagePath is sent to view using AppServiceProvider
        return view('user.purchase.purchaseDetail', [
            'purchase' => $purchase
        ]);
    }

    // Add shop product to logged user's basket
    public function addToBasket(Request $request)
    {
        // Validate quantity
        $this->validateQuantity($request);

        // User should not be able to add product which sale has ended, but it is checked
        $product = Product::where('id_product', '=', $request->productId)
                          ->first();
        if ($product->isSaleOver())
        {
            return back()->with('errorMessage', 'Predaj produktu bol ukonceny');
        }

        // Getting here means validation was successful
        $user = Auth::user();
        $basket = $user->getCurrentBasket();

        // Check if user already has product in his basket
        $basketProduct = BasketProduct::where('id_basket', '=', $basket->id_basket)
                                      ->where('id_product', '=', $request->productId)
                                      ->first();

        if (is_null($basketProduct))
        {
            // User does not have the product in his basket yet
            $basketProduct = BasketProduct::create([
                'id_basket' => $basket->id_basket,
                'id_product' => $request->productId,
                'quantity' => $request->quantityValue
            ]);
        }
        else
        {
            // User already has the product in his basket
            $currentQuantity = $basketProduct->quantity;
            $newQuantity = ($currentQuantity + $request->quantityValue > Constants::MAX_PRODUCT_PIECES)
                            ? Constants::MAX_PRODUCT_PIECES : ($currentQuantity + $request->quantityValue);

            // Because of composite primary key Query Builder must be used instead of Eloquent
            DB::table('basket_product')
                  ->where('id_basket', $basket->id_basket)
                  ->where('id_product', $request->productId)
                  ->update([
                     'quantity' => $newQuantity
            ]);
        }

        return back()->with('message', 'Produkt bol uspesne pridany do kosika');
    }

    // Removes product from user's basket
    public function destroyBasketProduct(Request $request)
    {
        // Because of composite primary key Query Builder must be used instead of Eloquent
        DB::table('basket_product')
            ->where('id_basket', $request->destroyBasketId)
            ->where('id_product', $request->destroyProductId)
            ->delete();

        return back()->with('message', 'Produkt bol uspesne zmazany z kosika');
    }

    // AJAX call to edit basket product quantity
    public function editBasketProductQuantity(Request $request)
    {
        // Validate quantity
        $this->validateQuantity($request);

        // Because of composite primary key Query Builder must be used instead of Eloquent
        DB::table('basket_product')
            ->where('id_basket', $request->basketId)
            ->where('id_product', $request->productId)
            ->update([
                'quantity' => $request->newBasketQuantity
        ]);

        $basketProduct = BasketProduct::where('id_basket', '=', $request->basketId)
                                      ->where('id_product', '=', $request->productId)
                                      ->first();
        $newTotalPrice = $basketProduct->getTotalPrice();
        $enoughInStock = ($request->newBasketQuantity <= $basketProduct->getProduct()->getWarehouseProduct()->quantity);

        return response()->json([
            'newTotalPrice' => $newTotalPrice,
            'enoughInStock' => $enoughInStock
        ]);
    }

    // AJAX call to get total purchase price
    public function getTotalPurchasePrice()
    {
        $user = Auth::user();
        $basket = $user->getCurrentBasket();

        $totalPurchasePrice = $basket->getTotalPrice();
        $totalPurchasePriceWithFee = $basket->getTotalPriceWithFee();

        return response()->json([
            'totalPurchasePrice' => $totalPurchasePrice,
            'totalPurchasePriceWithFee' => $totalPurchasePriceWithFee
        ]);
    }

    // AJAX call to get information if basket is orderable
    public function isBasketOrderable()
    {
        $basket = Auth::user()->getCurrentBasket();
        $isOrderable = $basket->isOrderable();

        return response()->json([
            'isOrderable' => $isOrderable
        ]);
    }

    // Confirm purchase page
    public function confirmPurchase()
    {
        $user = Auth::user();
        $basket = $user->getCurrentBasket();
        $isOrderable = $basket->isOrderable();

        if (!$isOrderable || $basket->getBasketProducts()->count() == 0)
        {
            // Do not allow user to enter confirm page, if basket is not orderable or is empty
            return redirect('/user/basket/show');
        }

        $cities = City::select('city')->groupBy('city')->get();
        $address = $user->getAddress();
        $currentCity = $address->getCity();

        return view('user.shop.confirm', [
            'user' => $user,
            'address' => $address,
            'currentCity' => $currentCity,
            'basket' => $basket,
            'cities' => $cities
        ]);
    }

    // Validate information (address, phone number) in purchase form
    public function makePurchase(Request $request)
    {
        // All fields are required
        $request->validate([
            '*' => 'required',
        ]);

        // If city/postal_code combination is not found, function returns false
        if (!ValidationHelper::validateCommon($request))
        {
            $validator = Validator::make($request->all(), []);
            $validator->errors()->add('city', 'Dana kombinacia PSC a mesta neexistuje.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Getting here means validation was successful
        $user = Auth::user();
        $basket = $user->getCurrentBasket();

        // User's phone number is updated to one entered in the form
        $user->phone_number = $request->phoneNumber;
        $user->save();

        // When user goes one page back after making the order he has empty basket, yet he can still
        // access the form, thus it is necessary to check if basket is empty
        if ($basket->getBasketProducts()->count() == 0)
        {
            return redirect('/');
        }

        // Close current basket and give new basket to the user
        $now = Carbon::now()->toDateTimeString();

        $basket->date_basket_end = $now;
        $basket->removeProductsFromWarehouse();
        $basket->save();
        $newBasket = Basket::create(['id_user' => $user->id_user]);

        $address = RecordCreatorHelper::createAddress($request);
        $purchaseStatus = PurchaseStatus::where('status', '=', 'pending')
                                        ->first();

        // Create new purchase
        $purchase = Purchase::create([
            'id_basket' => $basket->id_basket,
            'id_address' => $address->id_address,
            'id_status' => $purchaseStatus->id_status,
            'purchase_date' => $now
        ]);

        return redirect('/user/basket/confirmed')->with(['purchaseId' => $purchase->id_purchase]);
    }

    // Confirmed purchase page
    public function confirmedPurchase()
    {
        $purchaseId = Session::get('purchaseId');

        return is_null($purchaseId) ? redirect('/') : view('user.shop.confirmed');
    }

    // Create review of product from user
    public function storeReview(Request $request)
    {
        $request->validate([
            'comment' => 'max:' . Constants::MAX_REVIEW_COMMENT_CHARACTERS,
            'rating' => ['required', 'min:0', 'max:5']
        ]);
        $user = Auth::user();
        $product = Product::where('id_product', '=', $request->newReviewProductId)
                          ->first();

        // User should not be able to post form to create review if product's sale is over, but it is checked
        if ($product->isSaleOver())
        {
            return back()->with('errorMessage', 'Predaj produktu bol ukonceny, nie je mozne napisat recenziu');
        }

        // User should not be able to post form to create review if product already has review from him, but it is checked
        if ($product->hasReviewFromUser($user))
        {
            return back()->with('errorMessage', 'Na dany produkt ste uz napisali recenziu');
        }

        // User should not be able to post form to create review for product he did not buy, but it is checked
        if (!$product->didUserBuy($user))
        {
            return back()->with('errorMessage', 'Nie je mozne pridat recenziu na produkt, ktory ste nekupili');
        }

        Review::create([
            'id_user' => $user->id_user,
            'id_product' => $product->id_product,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('message', 'Recenzia bola uspesne vytvorena');
    }

    // Delete review of product from user
    // Review can be deleted by its author or user with role 'reviewManager'
    public function destroyReview(Request $request)
    {
        $loggedUser = Auth::user();

        if (is_null($loggedUser))
        {
            return back()->with('errorMessage', 'Vymazanie recenzie bolo neuspesne');
        }

        $review = Review::where('id_user', '=', $request->authorId)
                        ->where('id_product', '=', $request->destroyReviewProductId)
                        ->first();

        if (!Helper::hasRightsToDeleteReview($loggedUser, $review))
        {
            return back()->with('errorMessage', 'Vymazanie recenzie bolo neuspesne');
        }

        // Because of composite primary key Query Builder must be used instead of Eloquent
        DB::table('review')
            ->where('id_user', $request->authorId)
            ->where('id_product', $request->destroyReviewProductId)
            ->delete();

        return back()->with('message', 'Recenzia bola uspesna zmazana');
    }

    // AJAX call to get edit user's review of product
    // Review can be edited only by its author
    public function editReview(Request $request)
    {
        $review = Review::where('id_user', '=', $request->authorId)
                         ->where('id_product', '=', $request->productId)
                         ->first();
        $loggedUser = Auth::user();

        // Review can be modified by author or user with role 'reviewManager'
        if (!$loggedUser->ownsReview($review))
        {
            // Return 403 Forbidden status if user has no right to modify the review
            return response()->json(['message' => 'Na vykonanie akcie nie ste autorizovany'],403);
        }

        // Getting hear means the review can be edited by the user
        // Validate rating and comment
        $validation = Validator::make($request->all(), [
            'comment' => 'max:' . Constants::MAX_REVIEW_COMMENT_CHARACTERS,
            'rating' => ['required', 'min:0', 'max:5']
        ]);

        if ($validation->fails())
        {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()]);
        }

        // Because of composite primary key Query Builder must be used instead of Eloquent
        DB::table('review')
            ->where('id_user', $request->authorId)
            ->where('id_product', $request->productId)
            ->update([
                'comment' => $request->comment,
                'rating' => $request->rating
        ]);

        return response()->json(['success' => true]);
    }

    // Confirm purchase delivery (user can confirm purchase delivery if it has status delivered)
    public function confirmPurchaseDelivery(Request $request)
    {
        $user = Auth::user();
        $purchase = Purchase::where('id_purchase', '=', $request->purchaseId)
                            ->first();

        // Only the purchase owner should be able to post the form to confirm the purchase delivery
        // and only a purchase with status delivered can be confirmed, but it is checked
        if (!$purchase->isOwnedByUser($user) || !$purchase->hasStatus('delivered'))
        {
            return redirect('/')->with('errorMessage', 'Potvrdenie dorucenia objednavky bolo neuspesne');
        }

        // Getting here means purchase delivery can be confirmed
        $deliveredStatus = PurchaseStatus::where('status', '=', 'confirmed')
                                         ->first();
        $purchase->id_status = $deliveredStatus->id_status;
        $purchase->save();

        return back()->with('message', 'Dorucenie objedavnky bolo uspesne potvrdene');
    }


    // Helper method, called only internally
    public function validateQuantity(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'quantityValue' => ['numeric', 'min:1', 'max:' . Constants::MAX_PRODUCT_PIECES]
        ]);

        if ($validation->fails())
        {
            return back()->with('errorMessage', 'Nastala chyba pri validacii poctu kusov produktu');
        }

        return null;
    }
}
