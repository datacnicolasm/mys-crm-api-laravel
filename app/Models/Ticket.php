<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CRM_Tickets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = '';

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
    public $timestamps = true;

    /**
     * Get the type ticket that owns the ticket.
     */
    public function type():BelongsTo
    {
        return $this->belongsTo(TypeTicket::class, 'cod_type', 'idreg');
    }

    /**
     * Get the customer ticket that owns the ticket.
     */
    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cod_ter', 'cod_ter');
    }

    /**
     * Get the user ticket that owns the ticket.
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'cod_user', 'cod_mer');
    }
}
