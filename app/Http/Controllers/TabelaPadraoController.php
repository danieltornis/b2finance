<?php

namespace App\Http\Controllers;

use App\TabelaPadrao;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class TabelaPadraoController extends Controller
{
    public function index()
    {
        if(Gate::denies('tabela_padrao_visualizar')) {
            return view('nao_autorizado');
        }

        $tabelas_padrao = TabelaPadrao::orderby('TP_CHAVE','ASC')->get();

        return view('tabela_padrao.listar', [
            'tabelas_padrao' => $tabelas_padrao
        ]);
    }

    public function create()
    {
        if(Gate::denies('tabela_padrao_cadastrar')) {
            return view('nao_autorizado');
        }

        return view('tabela_padrao.cadastrar');
    }

    public function store(Request $request)
    {
        if(Gate::denies('tabela_padrao_cadastrar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'tabela' => 'required|max:20',
            'chave' => 'required|max:6|unique:tabela_padrao,TP_CHAVE,null,'.$request->chave.',TP_TABELA,'.$request->tabela,
            'descricao' => 'required|max:100',
            'parametro' => 'max:200');

        $messages = array(
            'tabela.required' => 'O campo Tabela é obrigatório!',
            'tabela.unique' => 'Tabela Padrão já cadastrada!',
            'tabela.max' => 'O campo Tabela deve ter no máximo 20 caracteres!',
            'chave.required' => 'O campo Chave é obrigatório!',
            'chave.max' => 'O campo Tabela deve ter no máximo 6 caracteres!',
            'descricao.required' => 'O campo Descrição é obrigatório!',
            'descricao.max' => 'O campo Tabela deve ter no máximo 100 caracteres!',
            'parametro.max' => 'O campo Parâmetro deve ter no máximo 200 caracteres!',
            'chave.unique' => 'Tabela Padrão já existente!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('tabela_padrao.cadastrar')
                ->withErrors($validator)
                ->withInput();
        } else {
            $tabela_padraoModel = new TabelaPadrao();
            $tabela_padraoModel->TP_TABELA = Input::get('tabela');
            $tabela_padraoModel->TP_CHAVE = Input::get('chave');
            $tabela_padraoModel->TP_DESCRICAO = Input::get('descricao');
            $tabela_padraoModel->TP_PARAMETRO = Input::get('parametro');
            $tabela_padraoModel->save();

            return Redirect::route('tabela_padrao');
        }
    }

    public function edit($id)
    {
        if(Gate::denies('tabela_padrao_editar')) {
            return view('nao_autorizado');
        }

        $tabela_padrao = TabelaPadrao::find($id);

        return view('tabela_padrao.editar' , [
            'tabela_padrao' => $tabela_padrao
        ]);
    }

    public function update(Request $request, $id)
    {
        if(Gate::denies('tabela_padrao_editar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'tabela' => 'required|max:20',
            'chave' => 'required|max:6',
            'descricao' => 'required|max:100',
            'parametro' => 'max:200'
        );

        $messages = array(
            'tabela.required' => 'O campo Tabela é obrigatório!',
            'tabela.unique' => 'Tabela Padrão já cadastrada!',
            'tabela.max' => 'O campo Tabela deve ter no máximo 20 caracteres!',
            'chave.required' => 'O campo Chave é obrigatório!',
            'chave.max' => 'O campo Tabela deve ter no máximo 6 caracteres!',
            'descricao.required' => 'O campo Descrição é obrigatório!',
            'descricao.max' => 'O campo Tabela deve ter no máximo 100 caracteres!',
            'parametro.max' => 'O campo Parâmetro deve ter no máximo 200 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('tabela_padrao.editar', $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            // store

            $tabela_padraoModel = TabelaPadrao::find($id);
            $tabela_padraoModel->TP_TABELA = Input::get('tabela');
            $tabela_padraoModel->TP_CHAVE = Input::get('chave');
            $tabela_padraoModel->TP_DESCRICAO = Input::get('descricao');
            $tabela_padraoModel->TP_PARAMETRO = Input::get('parametro');
            $tabela_padraoModel->save();

            Flash::success('Tabela Padrão alterada com sucesso!');

            return Redirect::route('tabela_padrao');
        }
    }

    public function destroy($id)
    {
        if(Gate::denies('tabela_padrao_excluir')) {
            return view('nao_autorizado');
        }

        $tabela_padraoModel = TabelaPadrao::find($id);
        $tabela_padraoModel->delete();

        Flash::success('Tabela Padrão excluída com sucesso!');

        return Redirect::route('tabela_padrao');
    }
}
