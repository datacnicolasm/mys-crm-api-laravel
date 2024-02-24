<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeTicket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CRM_Types_ticket';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idreg';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the tickets for the type.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Ticket::class, 'idreg', 'cod_type');
    }
}
