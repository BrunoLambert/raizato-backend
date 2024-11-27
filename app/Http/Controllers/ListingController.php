<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function getLowStock()
    {
        $stocks = Stock::with('product')
            ->orderBy('quantity')
            ->get()
            ->filter(function ($stock) {
                return $stock->quantity < $stock->product->minimum_stock;
            });

        return response($stocks);
    }

    public function getCloseToExpirationDate()
    {
        $stocks = Product::has('stock')
            ->with('stock')
            ->orderBy('expiration_date')
            ->get();

        return response($stocks);
    }
}
