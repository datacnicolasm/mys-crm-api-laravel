<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\ApiControler;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Map de columnas en el orden del DataTable (¡importante!)
        $columns = [
            0 => 'cod_ter',
            1 => 'nom_ter',
            2 => 'tel1',
            3 => 'email',
            4 => 'ciudad',
        ];

        // Parámetros DataTables
        $draw        = (int) $request->input('draw', 0);
        $start       = max(0, (int) $request->input('start', 0));
        $length      = (int) $request->input('length', 25);
        $length      = ($length > 0 && $length <= 100) ? $length : 25; // límite sano
        $searchValue = trim((string) data_get($request->input('search'), 'value', ''));

        // Orden
        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir    = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderColIdx] ?? 'cod_ter';

        // Query base
        $baseQuery = Customer::query()
            ->select(['idrow', 'cod_ter', 'nom_ter', 'dir', 'tel1', 'email', 'ciudad'])
            ->where('clasific', '1');

        // Total sin filtros (solo la condición de negocio)
        $recordsTotal = (clone $baseQuery)->count();

        // Filtros de búsqueda
        if ($searchValue !== '') {
            $baseQuery->where(function ($q) use ($searchValue) {
                $like = "%{$searchValue}%";
                $q->where('cod_ter', 'like', $like)
                    ->orWhere('nom_ter', 'like', $like)
                    ->orWhere('tel1',   'like', $like)
                    ->orWhere('email',  'like', $like)
                    ->orWhere('ciudad', 'like', $like);
            });
        }

        // Total filtrado (antes de paginar)
        $recordsFiltered = (clone $baseQuery)->count();

        // Orden + paginación
        $data = $baseQuery
            ->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Respuesta en formato DataTables
        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_one(Request $request)
    {
        $id = $request->input('idrow');
        $customers = Customer::with('tickets', 'tickets.type')
            ->where('clasific', '1')
            ->where('idrow', $id)
            ->get(['idrow', 'cod_ter', 'nom_ter', 'dir', 'tel1', 'email', 'ciudad']);

        return $this->showAll($customers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
