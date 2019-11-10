<?php

namespace App\Http\Controllers;

use App\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function show(Request $request, $id)
    {

        $evento = Evento::find($id);
        return view('evento.visualizar',[
            'evento' => $evento
        ]);
    }
}
