<?php

namespace App\Models;

use App\Enums\StockLogTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    use HasFactory;
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "quantity" => 0,
        "type" => "purchase"
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "quantity",
        "type",
        "user_id",
        "stock_id"
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => StockLogTypeEnum::class
        ];
    }

    /**
     * Get the user that creates this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stock related to this log.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
