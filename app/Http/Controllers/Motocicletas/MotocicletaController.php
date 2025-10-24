<?php

namespace App\Http\Controllers\Motocicletas;

use App\Http\Controllers\ApiControler;
use App\Models\Customer;
use App\Models\Motocicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MotocicletaController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Columnas en el orden EXACTO del DataTable
        $columns = [
            0 => 'm.placa',
            1 => 'm.chasis',
            2 => 'm.modelo',
            3 => 'm.ano',
            4 => 'm.cod_cli',
            5 => 'c.nom_ter',
        ];

        $draw   = (int) $request->input('draw', 0);
        $start  = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 25);
        $length = ($length > 0 && $length <= 100) ? $length : 25;

        $search = trim((string) data_get($request->input('search'), 'value', ''));

        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir    = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderColIdx] ?? 'm.placa';

        // Tabla real de cada modelo (evita hardcode si hay prefijos/esquemas)
        $motosTable = (new Motocicleta)->getTable(); // p.ej. 'motocicletas'
        $custTable  = (new Customer)->getTable();    // p.ej. 'Comae_ter'

        // Query base con JOIN (evita el WHERE IN enorme del eager load)
        $base = Motocicleta::from("$motosTable as m")
            ->leftJoin("$custTable as c", 'c.cod_ter', '=', 'm.cod_cli')
            ->select([
                'm.placa',
                'm.chasis',
                'm.modelo',
                'm.ano',
                'm.cod_cli',
                DB::raw('c.idrow as customer_idrow'),
                DB::raw('c.nom_ter as customer_nom_ter'),
            ]);

        // Total sin filtros (contar sobre todas las motos)
        $recordsTotal = Motocicleta::from("$motosTable as m")->count();

        // Búsqueda global
        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $like = "%{$search}%";
                $q->where('m.placa', 'like', $like)
                    ->orWhere('m.chasis', 'like', $like)
                    ->orWhere('m.modelo', 'like', $like)
                    ->orWhere('m.ano', 'like', $like)
                    ->orWhere('m.cod_cli', 'like', $like)
                    ->orWhere('c.nom_ter', 'like', $like);
            });
        }

        // Total filtrado
        // (si la relación pudiera duplicar, usa DISTINCT; si es 1:1 basta con count())
        $recordsFiltered = (clone $base)->count();

        // Orden y paginación
        $data = (clone $base)
            ->orderByRaw("$orderColumn $orderDir")
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
