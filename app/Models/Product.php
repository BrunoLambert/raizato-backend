<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        "expiration_date" => "2001-03-10",
    ];

    protected $fillable = [
        "name",
        "code",
        "description",
        "cost_price",
        "selling_price",
        "minimum_stock",
        "expiration_date",
        "category_id",
        "supplier_id"
    ];

    /**
     * Get the products for the supplier.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
