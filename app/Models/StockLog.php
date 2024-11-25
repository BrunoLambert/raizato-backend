<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */

    /**
     * Get the product for the stock.
     */
    protected $attributes = [
        "quantity" => 0,
        "type" => "purchase"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
