<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // Disable created_at/updated_at columns
    public $timestamps = false;


    protected $table = 'purchase';

    protected $fillable = [
        'id_basket', 'id_address', 'id_status', 'purchase_date', 'payment_date'
    ];


    // Primary key information
    protected $primaryKey = 'id_purchase';
    public $incrementing = true;


    // Relation to PurchaseStatus
    public function getStatus()
    {
        return PurchaseStatus::where('id_status', '=', $this->id_status)
                             ->first();
    }

    // Relation to Basket
    public function getBasket()
    {
        return Basket::where('id_basket', '=', $this->id_basket)
                     ->first();
    }

    // Total price as of the date of the order
    public function getTotalPrice()
    {
        $basket = $this->getBasket();
        return $basket->getTotalPrice();
    }

    // Price of product as of the date of the order
    public function getProductPrice($productId)
    {
        $product = Product::where('id_product', '=', $productId)
                          ->first();
        return $product->getPriceOfDate($this->purchase_date);
    }

    public function getFormattedProductPrice($productId)
    {
        $price = $this->getProductPrice($productId)->price;
        return number_format($price, 2, '.', ' ');
    }

    public function getBasketProduct($productId)
    {
        $basketId = $this->getBasket()->id_basket;
        return BasketProduct::where('id_product', '=', $productId)
                            ->where('id_basket', '=', $basketId)
                            ->first();
    }
}
