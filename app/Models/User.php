<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Disable created_at/updated_at columns
    public $timestamps = false;


    protected $table = 'web_user';

    protected $fillable = [
        'id_address', 'id_image', 'first_name', 'last_name', 'password', 'email', 'phone_number'
    ];


    // Primary key information
    protected $primaryKey = 'id_user';
    public $incrementing = true;


    // Relation to Address
    public function getAddress()
    {
        return Address::where('id_address', '=', $this->id_address)->first();
    }

    // Relation to UserRole
    public function getUserRoles()
    {
        return UserRole::where('id_user', '=', $this->id_user)
                       ->get();
    }

    // Relation to Image
    // Never returns null, user might not have image, but he always has record in Image table
    public function getImage()
    {
        return Image::where('id_image', '=', $this->id_image)->first();
    }

    // Relation to Purchase
    public function getPurchases()
    {
        return Purchase::whereIn('id_basket', function($mainQuery) {
                                 $mainQuery->select('id_basket')
                                           ->from('basket')
                                           ->where('id_user', '=', $this->id_user);
        })->get();
    }

    // Might return null if user has no image
    public function getImagePath()
    {
        $image = $this->getImage();

        return $image->image_path;
    }

    // If image on path does not exist, function returns null
    public function getImagePathIfExists()
    {
        $imagePath = $this->getImagePath();
        return Helper::imageExists($imagePath, 'users') ? $imagePath : null;
    }

    // Function returns true if logged user has at least one role from the array
    public function hasRole(array $roleNames)
    {
        foreach ($roleNames as $roleName)
        {
            $dbRole = WebRole::where('name', '=', $roleName)->first();

            if (UserRole::where('id_user', '=', $this->id_user)
                        ->where('id_role', '=', $dbRole->id_role)
                        ->exists())
            {
                return true;
            }
        }

        return false;
    }

    // Relation to Basket
    // Current basket is a basket where date_basket_end is null
    public function getCurrentBasket()
    {
        return Basket::where('id_user', '=', $this->id_user)
                     ->whereNull('date_basket_end')
                     ->first();
    }

    public function ownsReview($review)
    {
        return $review->id_user == $this->id_user;
    }
}
