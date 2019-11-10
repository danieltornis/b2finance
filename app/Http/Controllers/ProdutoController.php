<?php

namespace App\Http\Controllers;

set_time_limit(0);

use App\Cliente;
use App\Filial;
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

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('produto_visualizar')) {
            return view('nao_autorizado');
        }

        $produto = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        $produto_combo[""] = "Todos";
        foreach ($produto as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        $filial = Filial::orderby('filial_descricao','ASC')->get();
        $filial_combo = [];
        $filial_combo[""] = "Todas";
        foreach($filial as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        if ($request->filial) {
            $filial = $request->filial;
        } else {
            $filial = '';
        }

        if ($request->cliente) {
            $cliente = $request->cliente;
        } else {
            $cliente = '';
        }

        if ($request->produto) {
            $produto = $request->produto;
        } else {
            $produto = '';
        }

        if ($request->ativo) {
            $ativo = $request->ativo;
        } else {
            $ativo = '';
        }

        $ativo_combo["T"] = 'Todos';
        $ativo_combo["A"] = 'Ativo';
        $ativo_combo["I"] = 'Inativo';

            $rel = DB::connection('mysql')->Table('produto as p')->select('*')
                ->join('tbl_clt as c','c.clt_id','=','PROD_CLIENTE')
                ->join('tbl_filial as f','f.filial_cod','=','c.clt_filial')
                ->join('tabela_padrao as tp', function ($join) {
                    $join->On('tp.TP_CHAVE', '=', 'PROD_PRODUTO')->where('tp.TP_TABELA','produtos');
                });

            if ($filial != '') {
                $rel = $rel->where('c.clt_filial', $filial);
            }

            if ($cliente != '') {
                $rel = $rel->where('PROD_CLIENTE', $cliente);
            }

            if ($produto != '') {
                $rel = $rel->where('PROD_PRODUTO', $produto);
            }

            if ($ativo == 'S') {
                $rel = $rel->where('PROD_ATIVO', $ativo);
            }

            if ($ativo == 'I') {
                $rel = $rel->where('PROD_ATIVO', $ativo);
            }

            $rel = $rel->orderBy('clt_nome_razao','ASC')->get();


        return view('produto.listar', [
            'rel' => $rel,
            'filial_combo' => $filial_combo,
            'produto_combo' => $produto_combo,
            'ativo_combo' => $ativo_combo,
            'filial' => $filial,
            'cliente' => $cliente,
            'produto' => $produto,
            'ativo' => $ativo
        ]);
    }

    public function create()
    {
        if(Gate::denies('produto_liberar')) {
            return view('nao_autorizado');
        }

        $produto = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        foreach ($produto as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        $filial = Filial::orderby('filial_descricao','ASC')->get();
        $filial_combo = [];
        foreach($filial as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        $ativo_combo["A"] = 'Ativo';
        $ativo_combo["I"] = 'Inativo';

        return view('produto.liberar',[
            'filial_combo' => $filial_combo,
            'produto_combo' => $produto_combo,
            'ativo_combo' => $ativo_combo
        ]);
    }

    public function store(Request $request)
    {
        if(Gate::denies('produto_liberar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'filial' => 'required',
            'cliente' => 'required',
            'produto' => 'required',
            'cnpj' => 'required|min:14'
        );

        $messages = array(
            'filial.required' => 'O campo Filial é obrigatório!',
            'cliente.required' => 'O campo Cliente é obrigatório!',
            'produto.required' => 'O campo Produto é obrigatório!',
            'cnpj.required' => 'O campo CNPJ é obrigatório!',
            'cnpj.min' => 'O campo CNPJ deve ter no mínimo 14 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('produto.liberar')
                ->withErrors($validator)
                ->withInput();
        } else {
            $produtoModel = new Produto();
            $produtoModel->PROD_CLIENTE = Input::get('cliente');
            $produtoModel->PROD_PRODUTO = Input::get('produto');
            $produtoModel->PROD_CNPJ = Input::get('cnpj');
            $produtoModel->PROD_ATIVO = Input::get('ativo');
            if (Input::get('data_corte') != null) {
                $produtoModel->PROD_DT_CORTE = date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', Input::get('data_corte'))));
            }
            $produtoModel->save();

            Flash::success('Liberação de Produto realizada com sucesso!');

            return Redirect::route('produto');
        }
    }

    public function edit($id)
    {
        if(Gate::denies('produto_editar')) {
            return view('nao_autorizado');
        }

        $produto = Produto::find($id);

        $produto_cad = TabelaPadrao::where('TP_TABELA','produtos')->orderby('TP_DESCRICAO', 'ASC')->get();
        $produto_combo = [];
        foreach ($produto_cad as $tp) {
            $produto_combo[$tp->TP_CHAVE] = $tp->TP_DESCRICAO;
        }

        $filial = Filial::orderby('filial_descricao','ASC')->get();
        $filial_combo = [];
        foreach($filial as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        $ativo_combo["A"] = 'Ativo';
        $ativo_combo["I"] = 'Inativo';

        $filial = Cliente::find($produto->PROD_CLIENTE)->clt_filial;
        if($produto->PROD_DT_CORTE != null) {
            $data_corte = date('d/m/Y', strtotime(Carbon::createFromFormat('Y-m-d', $produto->PROD_DT_CORTE)));
        } else {
            $data_corte = '';
        }

        return view('produto.editar' , [
            'produto' => $produto,
            'filial' => $filial,
            'data_corte' => $data_corte,
            'filial_combo' => $filial_combo,
            'produto_combo' => $produto_combo,
            'ativo_combo' => $ativo_combo
        ]);
    }

    public function update(Request $request, $id)
    {
        if(Gate::denies('produto_editar')) {
            return view('nao_autorizado');
        }

        $rules = array(
            'filial' => 'required',
            'cliente' => 'required',
            'produto' => 'required',
            'cnpj' => 'required|min:14'
        );

        $messages = array(
            'filial.required' => 'O campo Filial é obrigatório!',
            'cliente.required' => 'O campo Cliente é obrigatório!',
            'produto.required' => 'O campo Produto é obrigatório!',
            'cnpj.required' => 'O campo CNPJ é obrigatório!',
            'cnpj.min' => 'O campo CNPJ deve ter no mínimo 14 caracteres!'
        );
        $validator = \Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::route('produto.editar', $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $produtoModel = Produto::find($id);
            $produtoModel->PROD_CLIENTE = Input::get('cliente');
            $produtoModel->PROD_PRODUTO = Input::get('produto');
            $produtoModel->PROD_CNPJ = Input::get('cnpj');
            $produtoModel->PROD_ATIVO = Input::get('ativo');
            if (Input::get('data_corte') != '') {
                $produtoModel->PROD_DT_CORTE = date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', Input::get('data_corte'))));
            } else {
                $produtoModel->PROD_DT_CORTE = null;
            }
            $produtoModel->save();

            Flash::success('Liberação de produto alterada com sucesso!');

            return Redirect::route('produto');
        }
    }

    public function destroy($id)
    {
        if(Gate::denies('produto_editar')) {
            return view('nao_autorizado');
        }

        $produtoModel = Produto::find($id);
        $produtoModel->delete();

        Flash::success('Liberação de produto excluída com sucesso!');

        return Redirect::route('produto');
    }

    public function buscarAjaxCliente(Request $request)
    {
        $filial_id = $request->filial_id;
        $clientes = DB::connection('mysql')->table('tbl_clt as c')->select('c.*')
            ->where('clt_filial', $filial_id)->orderBy('c.clt_nome_razao','ASC')->get();

        return json_encode($clientes);
    }
}