<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motocicleta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'InMae_bic';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'placa';

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
     * Get the customer that owns the motorcycles.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cod_cli', 'cod_ter');
    }
}
