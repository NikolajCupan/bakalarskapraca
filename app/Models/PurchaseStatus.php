<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model
{
    use HasFactory;

    // Disable created_at/updated_at columns
    public $timestamps = false;


    protected $table = 'purchase_status';

    protected $fillable = [
        'status'
    ];


    // Primary key information
    protected $primaryKey = 'id_status';
    public $incrementing = true;
}
