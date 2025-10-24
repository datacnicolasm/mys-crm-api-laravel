<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Linea;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'cod_mar', 'Cod_mar');
    }

    /**
     * Get the group that owns the product.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'cod_gru', 'cod_gru');
    }

    /**
     * Get the type that owns the product.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Linea::class, 'cod_tip', 'cod_tip');
    }

    /**
     * Get the reference that owns the product.
     */
    public function references(): HasMany
    {
        return $this->hasMany(TicketReference::class, 'cod_ref', 'cod_ref');
    }

    /**
     * Get saldo de bodega importaciones
     */
    public static function saldoImportaciones($codRef)
    {
        // Definir variable de bodega importaciones 900
        $codBod = '900';

        // Ejecutar el procedimiento almacenado
        $resultados = DB::select('EXEC InSaldosReferenciaBodegaNM @cod_ref = ?, @cod_bod = ?', [$codRef, $codBod]);

        // Verificar que hay resultados
        if (!empty($resultados)) {
            // Acceder al valor de Saldo_final del primer resultado
            $saldoFinal = $resultados[0]->Saldo_final;

            // Limpiar el valor (por ejemplo, eliminar espacios)
            $saldoFinal = floatval(trim($saldoFinal));

            return $saldoFinal;
        } else {
            return null;
        }
    }

    /**
     * Condicional para referencias con existencias
     */
    public static function condicionalExistencias($codRef)
    {
        $existencias_bogota = Product::saldoBogota($codRef);

        if ($existencias_bogota >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get saldo de bodega Bogota
     */
    public static function saldoBogota($codRef)
    {
        // Definir variable de bodega importaciones 900
        $codBod = '001';

        // Ejecutar el procedimiento almacenado
        $resultados = DB::select('EXEC InSaldosReferenciaBodegaNM @cod_ref = ?, @cod_bod = ?', [$codRef, $codBod]);

        // Verificar que hay resultados
        if (!empty($resultados)) {
            // Acceder al valor de Saldo_final del primer resultado
            $saldoFinal = $resultados[0]->Saldo_final;

            // Limpiar el valor (por ejemplo, eliminar espacios)
            $saldoFinal = floatval(trim($saldoFinal));

            return $saldoFinal;
        } else {
            return null;
        }
    }

    /**
     * Get saldo de bodega Bogota E-COMMERCE
     */
    public static function saldoEcommerce($codRef)
    {
        // Definir variable de bodega importaciones 900
        $codBod = '001';

        // Ejecutar el procedimiento almacenado
        $resultados = DB::select('EXEC InSaldosReferenciaBodegaNM @cod_ref = ?, @cod_bod = ?', [$codRef, $codBod]);

        // Verificar que hay resultados
        if (!empty($resultados)) {
            return $resultados[0];
        } else {
            return null;
        }
    }

    /**
     * Get saldo de bodega Bogota
     */
    public static function saldoMedellin($codRef)
    {
        // Definir variable de bodega importaciones 900
        $codBod = '008';

        // Ejecutar el procedimiento almacenado
        $resultados = DB::select('EXEC InSaldosReferenciaBodegaNM @cod_ref = ?, @cod_bod = ?', [$codRef, $codBod]);

        // Verificar que hay resultados
        if (!empty($resultados)) {
            // Acceder al valor de Saldo_final del primer resultado
            $saldoFinal = $resultados[0]->Saldo_final;

            // Limpiar el valor (por ejemplo, eliminar espacios)
            $saldoFinal = floatval(trim($saldoFinal));

            return $saldoFinal;
        } else {
            return null;
        }
    }

}
