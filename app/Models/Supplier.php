<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "name" => "",
        "cnpj" => "XXXXXXXX0001AA",
        "contact" => ""
    ];

    /**
     * Get the products for the supplier.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
