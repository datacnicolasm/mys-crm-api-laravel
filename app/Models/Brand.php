<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'InMae_mar';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'Cod_mar';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the products for the brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'Cod_mar', 'cod_mar');
    }
}
