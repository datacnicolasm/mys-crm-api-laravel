<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiControler;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $user = User::where('cod_mer', $request->input('cod_mer'))
                        ->get();

        return $this->showAll($user);
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

    public function noticesUser(Request $request)
    {
        $user = User::find($request->input('cod_mer'));

        // Verifica si el usuario existe
        if (!$user) {
            return Response::json(['message' => 'Usuario no encontrado'], 404);
        }

        // Obtén las notificaciones del usuario
        $notices = $user->tickets()
                ->with('notices')
                ->get()
                ->pluck('notices')
                ->flatten()
                ->sortByDesc('created_at');

        return $this->showAll($notices);
    }

    public function userTicketsReferencias(Request $request){
        $user = User::find($request->input('cod_mer'));

        // Verifica si el usuario existe
        if (!$user) {
            return Response::json(['message' => 'Usuario no encontrado'], 404);
        }

        // Obtener las referencias de los tickets
        $referencias = $user->tickets()
                ->where('cod_type', 1)
                ->whereNotIn('cod_estado', [2, 3])
                ->with('references')
                ->get()
                ->pluck('references')
                ->flatten()
                ->filter(function ($reference) {
                    return Product::condicionalExistencias($reference->cod_ref);
                });

        return $this->showAll($referencias);
    }
}
