<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     * Get the category for the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier for the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the stock of the product.
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
