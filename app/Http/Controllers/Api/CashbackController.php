<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\CashbackResource;

class CashbackController extends Controller
{
    
    public function __invoke(Request $request)
    {
        $coffee = $request->only(['Ristretto', 'Espresso', 'Lungo']);

        $coffee = collect($coffee)->map(function ($quantity, $product ) {
        return Product::where('name', $product)->first()->calculateCashback($quantity);         
    
        });

        return new CashbackResource($coffee);

    }
}
