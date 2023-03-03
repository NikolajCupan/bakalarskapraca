<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\PaginationHelper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    // Show products from category
    public function showCategory($category)
    {
        // As class Category and its attribute category have same name, it needs to be renamed first
        $categoryName = $category;
        $category = Category::where('category', '=', $categoryName)->first();

        $categories = Category::all();
        $productsFromCategory = $category->getSellingProducts();

        $paginatedProducts = PaginationHelper::paginate($productsFromCategory, 12);

        // user, basket and imagePath is sent to view using AppServiceProvider
        return view('shop.category', [
            'categories' => $categories,
            'activeCategory' => $category,
            'productsFromCategory' => $paginatedProducts
        ]);
    }

    // Show single product page
    public function showProduct($id_product)
    {
        $product = Product::where('id_product', '=', $id_product)
                          ->first();
        $reviews = $product->getReviews();
        $percentageRatings = $product->getPercentageRatings();
        $absoluteRatings = $product->getAbsoluteRatings();

        // user, basket and imagePath is sent to view using AppServiceProvider
        return view('shop.product', [
            'product' => $product,
            'reviews' => $reviews,
            'percentageRatings' => $percentageRatings,
            'absoluteRatings' => $absoluteRatings
        ]);
    }
}
