<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Usuario;
use App\PerfilUsuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class PerfilUsuarioController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('usuario_perfil_visualizar')) {
            return view('nao_autorizado');
        }

        $perfis = Perfil::orderBy('PER_NOME','ASC')->get();
        $perfil_options = [];
        foreach($perfis as $perfil) {
            $perfil_options[$perfil->PER_ID] = $perfil->PER_NOME;
        }

        $usuarios = Usuario::orderBy('name','ASC')->get();
        $usuario_options = [];
        foreach($usuarios as $usu) {
            $usuario_options[$usu->id] = $usu->name;
        }

        $rel = DB::Table('perfil_usuario as pu')->select('PU_ID', 'u.name as name', 'p.PER_NOME as PER_NOME')
            ->join('user as u','u.id','=','pu.PU_USU_ID')
            ->join('perfil as p','p.PER_ID','=','pu.PU_PER_ID');

        if ($request->usuario) {
            $usuario = $request->usuario;
            $rel = $rel->where('u.id', $usuario);
        } else {
            $usuario = '';
        }

        if ($request->perfil) {
            $perfil = $request->perfil;
            $rel = $rel->where('p.PER_ID', $perfil);
        } else {
            $perfil = '';
        }

        return view('perfil.perfilusuario', [
            'perfil_usuarios' => $rel->get(),
            'perfil_options' => $perfil_options,
            'usuario_options' => $usuario_options,
            'usuario' => $usuario,
            'perfil' => $perfil
        ]);
    }

    public function create()
    {
        if(Gate::denies('usuario_perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        $perfis = Perfil::orderBy('PER_NOME','ASC')->get();

        $perfil_options = [];
        foreach($perfis as $perfil) {
            $perfil_options[$perfil->PER_ID] = $perfil->PER_NOME;
        }

        $usuarios = Usuario::orderBy('name','ASC')->get();

        $usuario_options = [];
        foreach($usuarios as $usuario) {
            $usuario_options[$usuario->id] = $usuario->name;
        }

        return view('perfil.perfilusuariocadastrar', [
            'perfil_options' => $perfil_options,
            'usuario_options' => $usuario_options
        ]);
    }

    public function store(Request $request)
    {
        if(Gate::denies('usuario_perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'perfil' => 'required',
            'usuario' => 'required|unique:perfil_usuario,PU_USU_ID,null,'.Input::get('usuario').',PU_PER_ID,'.Input::get('perfil')
        );

        $messages = array(
            'perfil.required' => 'O campo Perfil é obrigatório!',
            'usuario.required' => 'O campo Usuário é obrigatório!',
            'usuario.unique' => 'Relacionamento entre Usuário vs Perfil já existente!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('perfilusuario.perfilusuariocadastrar')
                ->withErrors($validator)
                ->withInput();
        } else {
            $perfil_usuarioModel = new PerfilUsuario();
            $perfil_usuarioModel->PU_PER_ID = Input::get('perfil');
            $perfil_usuarioModel->PU_USU_ID = Input::get('usuario');
            $perfil_usuarioModel->save();

            return Redirect::route('perfilusuario');
        }
    }

    public function destroy($id)
    {
        if(Gate::denies('usuario_perfil_excluir')) {
            return view('nao_autorizado');
        }

        $perfil_usuarioModel = PerfilUsuario::find($id);
        $perfil_usuarioModel->delete();

        Flash::success('Relacionamento entre Usuário vs Perfil excluído com sucesso!');

        return Redirect::route('perfilusuario');
    }

}

