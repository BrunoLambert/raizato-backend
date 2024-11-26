<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "quantity" => 0
    ];

    protected $fillable = [
        "quantity",
        "product_id"
    ];

    /**
     * Get the product for the stock.
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}
