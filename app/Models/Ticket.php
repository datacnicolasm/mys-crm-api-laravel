<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{

    protected $fillable = [
        'cod_type',
        'cod_user',
        'cod_ter',
        'cod_ref',
        'title_ticket',
        'des_ticket',
        'cod_pipeline',
        'cod_estado',
        'fecha_aded'
    ];

    //protected $casts = ['fecha_aded' => 'datetime:Y-m-d H:00'];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    //protected $dateFormat = 'AAAA-DD-MM hh:mm:ss[.nnn]';

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
     * Get the type ticket that owns the ticket.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeTicket::class, 'cod_type', 'idreg');
    }

    /**
     * Get the customer ticket that owns the ticket.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cod_ter', 'cod_ter');
    }

    /**
     * Get the user ticket that owns the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cod_user', 'cod_mer');
    }

    /**
     * Get the user creator ticket that owns the ticket.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cod_creator', 'cod_mer');
    }
}
