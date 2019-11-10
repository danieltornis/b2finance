<?php

namespace App\Http\Controllers;

use App\Filial;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{
    public function index(){

        return view('index');

    }

    public function ambiente(Request $request) {

        $filial_sel = '';

        $filiais = DB::Table('tbl_filial as fil')->select('fil.filial_cod as filial_cod','fil.filial_descricao as filial_descricao')
            ->join('tbl_usuario_filial as ufil', function ($join) {
                $join->On('ufil.filial', '=', 'fil.filial_cod')->Where('ufil.usuario', '=', auth()->user()->login);
            })->OrderBy('fil.filial_descricao', 'ASC')->get();

        $filial_combo = [];
        foreach($filiais as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        if(Session::get('filial')=='') {
            if (count($filial_combo) == 1) {
                $filial_sel = key($filial_combo);
            }
        } else {
            $filial_sel = Session::get('filial');
        }

        return view('ambiente.listar', [
            'filial' => $filial_combo,
            'filial_sel' => $filial_sel
        ]);
    }

    public function ambienteChange(Request $request) {

        $rules = array(
            'filial_sel' => 'required'
        );

        $messages = array(
            'filial_sel.required' => 'O campo Filial é obrigatório!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {

            Session::put('filial', '');
            Session::put('ambiente', '');

            return Redirect::route('ambiente')
                ->withErrors($validator)
                ->withInput();
        } else {
            Session::put('filial', $request->filial_sel);

            $filial = Filial::find($request->filial_sel);

            Session::put('ambiente', "Filial: " . $filial->filial_descricao);

            return redirect()->route(Session::get('rota'));
        }
    }
}
