<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('cliente_visualizar')) {
            return view('nao_autorizado');
        }

        if(Session::get('rota')=='' || Session::get('rota') != Route::currentRouteName()) {
            Session::put('rota', Route::currentRouteName());
            return redirect()->route('ambiente');
        }

        $gerente = Usuario::where('gerente','1')->orderby('name', 'ASC')->get();
        $gerente_array = [];
        $gerente_array[""] = "Todos";
        foreach ($gerente as $ger) {
            $gerente_array[$ger->id] = $ger->name;
        }

        $executivo_vendas = Usuario::where('executivo_vendas','1')->orderby('name', 'ASC')->get();
        $executivo_vendas_array = [];
        $executivo_vendas_array[""] = "Todos";
        foreach ($executivo_vendas as $ev) {
            $executivo_vendas_array[$ev->id] = $ev->name;
        }

        $clientes = Cliente::where('clt_filial',Session::get("filial"))->orderby('clt_nome_razao', 'ASC')->get();
        $clientes_array = [];
        $clientes_array[""] = "Todos";
        foreach ($clientes as $cli) {
            $clientes_array[$cli->clt_id] = $cli->clt_nome_razao;
        }

        if ($request->codigo) {
            Session::put('codigo', $request->codigo);
        }

        if ($request->nome) {
            Session::put('nome', $request->nome);
        }

        if(!Empty($request->gerente[0])) {
            Session::put('gerente', $request->gerente);
        }

        if(!Empty($request->executivo_vendas[0])) {
            Session::put('executivo_vendas', $request->executivo_vendas);
        }

        if (Session::get('codigo') || Session::get('nome') || Session::get('gerente') || Session::get('executivo_vendas')) {
            $rel = DB::Table('tbl_clt as cli')->select('u1.name as gerente','u2.name as executivo_vendas', 'cli.*')
                ->leftjoin('user as u1', 'u1.id', '=', 'cli.clt_gerente')
                ->leftjoin('user as u2', 'u2.id', '=', 'cli.clt_execvendas')
                ->where('cli.clt_filial', Session::get('filial'));

            if (Session::get('codigo')) {
                $rel = $rel->wherein('cli.clt_id', Session::get('codigo'));
            }

            if (Session::get('nome') != '') {
                $rel = $rel->where('cli.clt_id', Session::get('nome'));
            }

            if(!empty(Session::get('projeto')) && Session::get('projeto') != '') {
                $rel = $rel->where('proj.equip_id','=',Session::get('projeto'));
            }

            if (Session::get('ativo') != '') {
                $rel = $rel->where('proj.equip_ativo', Session::get('ativo'));
            }

            if (Session::get('tipo') != '') {
                $rel = $rel->where('proj.equip_tipo', Session::get('tipo'));
            }

            $rel = $rel->orderBy('c.clt_nome_razao','ASC')->orderBy('proj.equip_nome','ASC')->get();
        } else {
            $rel = [];
        }

        return view('cadastros.projeto.listar', [
            'rel' => $rel,
            'coordenadores_combo' => $coordenadores_array,
            'coordenador' => Session::get('coordenador'),
            'clientes' => $cliente_combo,
            'cliente' => Session::get('cliente'),
            'status_combo' => $status_combo,
            'status' => Session::get('ativo'),
            'tipo_combo' => $tipo_combo,
            'tipo' => Session::get('tipo')
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
