<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "name" => "",
        "code" => "XXXXXXXXXX",
        "description" => "",
        "cost_price" => 0.00,
        "selling_price" => 0.00,
        "minimum_stock" => 1,
        "expiration_date" => "2001-03-10"
    ];

    /**
     * Get the products for the supplier.
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }
}
