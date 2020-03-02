<?php

namespace App\Http\Controllers;

set_time_limit(0);

use App\Link;
use App\Produto;
use App\TabelaPadrao;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Monolog\Handler\ElasticSearchHandlerTest;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('link_visualizar')) {
            return view('nao_autorizado');
        }

        $produto = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        $produto_combo[""] = "Todos";
        foreach ($produto as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        if ($request->produto) {
            $produto = $request->produto;
        } else {
            $produto = '';
        }

        $rel = DB::connection('mysql')->Table('link as l')->select('*')
            ->join('tabela_padrao as tp', function ($join) {
                $join->On('tp.TP_CHAVE', '=', 'LIN_PRODUTO')->where('tp.TP_TABELA','produtos');
            });

        if ($produto != '') {
            $rel = $rel->where('LIN_PRODUTO', $produto);
        }

        $rel = $rel->orderBy('LIN_CODIGO','ASC')->get();


        return view('link.listar', [
            'rel' => $rel,
            'produto_combo' => $produto_combo,
            'produto' => $produto
        ]);
    }

    public function create()
    {
        if(Gate::denies('link_cadastrar')) {
            return view('nao_autorizado');
        }

        $produto = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        foreach ($produto as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        return view('link.cadastrar',[
            'produto_combo' => $produto_combo
        ]);
    }

    public function store(Request $request)
    {
        if(Gate::denies('link_cadastrar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'codigo' => 'required|max:6',
            'versao' => 'required|max:10',
            'produto' => 'required',
            'link' => 'required|max:250'
        );

        $messages = array(
            'codigo.required' => 'O campo Código é obrigatório!',
            'codigo.max' => 'O campo Código deve ter no máximo 6 caracteres!',
            'versao.required' => 'O campo Versão é obrigatório!',
            'versao.max' => 'O campo Versão deve ter no máximo 10 caracteres!',
            'produto.required' => 'O campo Produto é obrigatório!',
            'link.required' => 'O campo Link é obrigatório!',
            'link.max' => 'O campo Link deve ter no máximo 250 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('link.cadastrar')
                ->withErrors($validator)
                ->withInput();
        } else {
            $linkModel = new Link();
            $linkModel->LIN_CODIGO = Input::get('codigo');
            $linkModel->LIN_VERSAO = Input::get('versao');
            $linkModel->LIN_PRODUTO = Input::get('produto');
            $linkModel->LIN_LINK = Input::get('link');
            if (Input::get('data_corte') != null) {
                $linkModel->LIN_DT_CORTE = date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', Input::get('data_corte'))));
            }
            $linkModel->save();

            Flash::success('Link cadastrado com sucesso!');

            return Redirect::route('link');
        }
    }

    public function edit($id)
    {
        if(Gate::denies('link_editar')) {
            return view('nao_autorizado');
        }

        $link = Link::find($id);

        $produto_cad = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        foreach ($produto_cad as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        if($link->LIN_DT_CORTE != null) {
            $data_corte = date('d/m/Y', strtotime(Carbon::createFromFormat('Y-m-d', $link->LIN_DT_CORTE)));
        } else {
            $data_corte = '';
        }

        return view('link.editar' , [
            'link' => $link,
            'data_corte' => $data_corte,
            'produto_combo' => $produto_combo
        ]);
    }

    public function update(Request $request, $id)
    {
        if(Gate::denies('link_editar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'codigo' => 'required|max:6',
            'versao' => 'required|max:10',
            'produto' => 'required',
            'link' => 'required|max:250'
        );

        $messages = array(
            'codigo.required' => 'O campo Código é obrigatório!',
            'codigo.max' => 'O campo Código deve ter no máximo 6 caracteres!',
            'versao.required' => 'O campo Versão é obrigatório!',
            'versao.max' => 'O campo Versão deve ter no máximo 10 caracteres!',
            'produto.required' => 'O campo Produto é obrigatório!',
            'link.required' => 'O campo Link é obrigatório!',
            'link.max' => 'O campo Link deve ter no máximo 250 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('link.editar', $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $linkModel = Link::find($id);
            $linkModel->LIN_CODIGO = Input::get('codigo');
            $linkModel->LIN_VERSAO = Input::get('versao');
            $linkModel->LIN_PRODUTO = Input::get('produto');
            $linkModel->LIN_LINK = Input::get('link');
            if (Input::get('data_corte') != '') {
                $linkModel->LIN_DT_CORTE = date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', Input::get('data_corte'))));
            } else {
                $linkModel->LIN_DT_CORTE = null;
            }
            $linkModel->save();

            Flash::success('Link alterado com sucesso!');

            return Redirect::route('link');
        }
    }

    public function destroy($id)
    {
        if(Gate::denies('link_editar')) {
            return view('nao_autorizado');
        }

        $linkModel = Link::find($id);
        $linkModel->delete();

        Flash::success('Link excluído com sucesso!');

        return Redirect::route('link');
    }
}
