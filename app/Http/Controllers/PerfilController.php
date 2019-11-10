<?php

namespace App\Http\Controllers;

use App\Acesso;
use App\Perfil;
use App\PerfilAcesso;
use App\PerfilUsuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class PerfilController extends Controller
{
    public function index()
    {
        if(Gate::denies('perfil_visualizar')) {
            return view('nao_autorizado');
        }

        $perfis = Perfil::all();

        return view('perfil.listar', [
            'perfis' => $perfis
        ]);
    }

    public function create()
    {
        if(Gate::denies('perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        return view('perfil.cadastrar');
    }

    public function store(Request $request)
    {
        if(Gate::denies('perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'nome' => 'required|max:60|unique:perfil,PER_NOME'
        );

        $messages = array(
            'nome.required' => 'O campo Nome é obrigatório!',
            'nome.max' => 'O campo Nome deve conter no máximo 60 caracteres!',
            'nome.unique' => 'Perfil já cadastrado!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('perfil.cadastrar')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store

            $perfilModel = new Perfil();
            $perfilModel->PER_NOME = Input::get('nome');
            $perfilModel->save();

            return Redirect::route('perfil');
        }
    }

    public function edit($id)
    {
        if(Gate::denies('perfil_editar')) {
            return view('nao_autorizado');
        }

        $perfil = Perfil::find($id);

        return view('perfil.editar' , [
            'perfil' => $perfil
        ]);
    }

    public function access($id)
    {
        if(Gate::denies('acesso_perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        $permissao = PerfilAcesso::where('PA_PER_ID',$id)->get();
        $per_combo = [];
        foreach($permissao as $per) {
            $per_combo[$per->PA_ACE_ID] = $per->PA_ACE_ID;
        }

        $perfil = Perfil::find($id);
        $acesso = Acesso::Orderby('ACE_DESCRICAO','ASC')->get();

        return view('perfil.acesso' , [
            'perfil' => $perfil,
            'permissao' => $per_combo,
            'acesso' => $acesso
        ]);
    }

    public function update(Request $request, $id)
    {
        if(Gate::denies('perfil_editar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'nome' => 'required|max:60|unique:perfil,PER_NOME,'.$id.',PER_ID'
        );

        $messages = array(
            'nome.required' => 'O campo Nome é obrigatório!',
            'nome.max' => 'O campo Nome deve conter no máximo 60 caracteres!',
            'nome.unique' => 'Perfil já cadastrado!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('perfil.editar', $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $perfilModel = Perfil::find($id);
            $perfilModel->PER_NOME = Input::get('nome');
            $perfilModel->save();

            Flash::success('Perfil alterado com sucesso!');

            return Redirect::route('perfil');
        }
    }

    public function access_update(Request $request, $id)
    {
        if(Gate::denies('acesso_perfil_cadastrar')) {
            return view('nao_autorizado');
        }

        $paModel = PerfilAcesso::where('PA_PER_ID',$id)->get();
        foreach($paModel as $pa) {
            $pa->delete();
        }

        if($request->get('checkbox')!='') {
            foreach ($request->get('checkbox') as $pa) {
                $paModel = new PerfilAcesso();
                $paModel->PA_PER_ID = $id;
                $paModel->PA_ACE_ID = $pa;
                $paModel->save();
            }
        }

        Flash::success('Acesso do Perfil alterado com sucesso!');

        return Redirect::route('perfil');
    }

    public function destroy($id)
    {
        if(Gate::denies('perfil_excluir')) {
            return view('nao_autorizado');
        }

        $lDel = true;
        $msg = '';

        $paModel = PerfilAcesso::where('PA_PER_ID',$id)->get();
        if($paModel->count()>0) {
            $lDel = false;
            $msg = 'Perfil não pode ser excluído pois existe(m) acesso(s) amarrado a este perfil!';
        }

        if($lDel) {
            $puModel = PerfilUsuario::where('PU_PER_ID', $id)->get();
            if ($puModel->count()>0) {
                $lDel = false;
                $msg = 'Perfil não pode ser excluído pois existe(m) usuário(s) amarrado a este perfil!';
            }
        }

        if($lDel) {
            $perfilModel = Perfil::find($id);
            $perfilModel->delete();

            Flash::success('Perfil excluído com sucesso!');
        } else {
            Flash::warning($msg);
        }
        return Redirect::route('perfil');
    }

}
