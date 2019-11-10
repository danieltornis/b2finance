<?php

namespace App\Http\Controllers;

use App\Acesso;
use App\PerfilAcesso;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class AcessoController extends Controller
{
    public function index()
    {
        if(Gate::denies('acesso_visualizar')) {
            return view('nao_autorizado');
        }

        $acessos = Acesso::all();

        return view('acesso.listar', [
            'acessos' => $acessos
        ]);
    }

    public function create()
    {
        if(Gate::denies('acesso_cadastrar')) {
            return view('nao_autorizado');
        }

        return view('acesso.cadastrar');
    }

    public function store(Request $request)
    {
        if(Gate::denies('acesso_cadastrar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'permissao' => 'required|max:30|unique:acesso,ACE_PERMISSAO',
            'descricao' => 'required|max:100'
        );

        $messages = array(
            'nome.required' => 'O campo Permissão é obrigatório!',
            'nome.max' => 'O campo Permissão deve ter no máximo 30 caracteres!',
            'nome.unique' => 'Acesso já cadastrado!',
            'descricao.required' => 'O campo Descrição é obrigatório!',
            'descricao.max' => 'O campo Descrição deve ter no máximo 100 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('acesso.cadastrar')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store

            $acessoModel = new Acesso();
            $acessoModel->ACE_PERMISSAO = Input::get('permissao');
            $acessoModel->ACE_DESCRICAO = Input::get('descricao');
            $acessoModel->ACE_ATIVO = 'S';
            $acessoModel->save();

            Flash::success('Acesso '. Input::get('descricao') .' cadastrado com sucesso!');

            return Redirect::route('acesso.cadastrar');
        }
    }

    public function edit($id)
    {
        if(Gate::denies('acesso_editar')) {
            return view('nao_autorizado');
        }

        $acesso = Acesso::find($id);

        return view('acesso.editar' , [
            'acesso' => $acesso
        ]);
    }

    public function update(Request $request, $id)
    {
        if(Gate::denies('acesso_editar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'permissao' => 'required|max:30|unique:acesso,ACE_PERMISSAO,'.$id.',ACE_ID',
            'descricao' => 'required|max:100',
            'ativo' => 'required'
        );

        $messages = array(
            'permissao.required' => 'O campo Permissão é obrigatório!',
            'permissao.max' => 'O campo Permissão deve ter no máximo 30 caracteres!',
            'permissao.unique' => 'Acesso já cadastrado!',
            'descricao.required' => 'O campo Descrição é obrigatório!',
            'descricao.max' => 'O campo Descrição deve ter no máximo 100 caracteres!',
            'ativo.required' => 'O campo Ativo é obrigatório!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('acesso.editar', $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            // store

            $acessoModel = Acesso::find($id);
            $acessoModel->ACE_PERMISSAO = Input::get('permissao');
            $acessoModel->ACE_DESCRICAO = Input::get('descricao');
            $acessoModel->ACE_ATIVO = Input::get('ativo');
            $acessoModel->save();

            Flash::success('Acesso alterado com sucesso!');

            return Redirect::route('acesso');
        }
    }

    public function destroy($id)
    {
        if(Gate::denies('acesso_excluir')) {
            return view('nao_autorizado');
        }

        $paModel = PerfilAcesso::where('PA_ACE_ID',$id)->get();
        if($paModel->count()>0) {
            Flash::warning('Acesso não pode ser excluído pois está amarrado a algum perfil!');
        } else {
            $acessoModel = Acesso::find($id);
            $acessoModel->delete();

            Flash::success('Acesso excluído com sucesso!');
        }

        return Redirect::route('acesso');
    }
}
