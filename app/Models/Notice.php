<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ticket',
        'cod_user',
        'title',
        'text_notice',
        'created_at',
        'updated_at'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CRM_notice';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the ticket that owns the notice.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Notice::class, 'id_ticket', 'idreg');
    }

    /**
     * Get the user that owns the notice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cod_user', 'cod_mer');
    }
}














