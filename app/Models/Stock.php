<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock extends Model
{
    use HasFactory;
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "quantity" => 0
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "quantity",
        "product_id"
    ];

    /**
     * Get the product for the stock.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the Logs for the stock.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}
