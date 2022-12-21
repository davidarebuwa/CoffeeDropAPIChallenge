<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prices',
    ];

    protected $casts = [
        'prices' => 'array',
    ];

    /**
     * Calculate total cashback for a given quantity of this product.
     * 
     * @param int $quantity
     * 
     * @return float
     */
   public function calculateCashback(int $quantity): float
    {

        if ($quantity > 0 && $quantity <= 50) {
            return $this->prices['0'] * $quantity;
        }

        if ($quantity > 50 && $quantity <= 500) {
            return $this->prices['51'] * $quantity;
        }

        if ($quantity > 500) {
            return $this->prices['501'] * $quantity;
        }


    }
}
