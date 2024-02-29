<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketReference extends Model
{
    protected $fillable = [
        'idreg_ticket',
        'cod_ref',
        'cantidad',
        'val_uni'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CRM_Ticke_refs';

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
     * Get the ticket that owns the reference.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'idreg_ticket', 'idreg');
    }

    /**
     * Get the producto that ownd the reference
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'cod_ref', 'cod_ref');
    }
}
