<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Linea;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'description'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'InMae_ref';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cod_ref';

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
     * Get the brand that owns the product.
     */
    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class, 'cod_mar', 'Cod_mar');
    }

    /**
     * Get the group that owns the product.
     */
    public function group():BelongsTo
    {
        return $this->belongsTo(Group::class, 'cod_gru', 'cod_gru');
    }

    /**
     * Get the type that owns the product.
     */
    public function type():BelongsTo
    {
        return $this->belongsTo(Linea::class, 'cod_tip', 'cod_tip');
    }
}














