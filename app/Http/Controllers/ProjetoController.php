<?php

namespace App\Http\Controllers;

set_time_limit(0);

use App\Filial;
use App\Projeto;
use App\ProjetoUsuario;
use App\Usuario;
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
use Illuminate\Support\Facades\Route;

class ProjetoController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('projeto_visualizar')) {
            return view('nao_autorizado');
        }

        if(Session::get('rota')=='' || Session::get('rota') != Route::currentRouteName()) {
            Session::put('rota', Route::currentRouteName());
            return redirect()->route('ambiente');
        }

        $coordenadores = Usuario::where('coordenador_projetos','1')->orderby('name', 'ASC')->get();
        $coordenadores_array = [];
        $coordenadores_array[""] = "Todos";
        foreach ($coordenadores as $co) {
            $coordenadores_array[$co->id] = $co->name;
        }

        if(!Empty($request->coordenador[0])) {
            Session::put('coordenador', $request->coordenador);
        }

        if ($request->cliente) {
            Session::put('cliente', $request->cliente);
        }

        if($request->projeto_sel) {
            Session::put('projeto', $request->projeto_sel);
        }

        if ($request->ativo) {
            Session::put('ativo', $request->ativo);
        }

        if ($request->tipo) {
            Session::put('tipo', $request->tipo);
        }

        $tabela_padrao = TabelaPadrao::Where('TP_TABELA','status_projeto')->orderby('TP_CHAVE','ASC')->get();
        $status_combo = [];
        $status_combo[""] = "Todos";
        foreach($tabela_padrao as $tp) {
            $status_combo[$tp->TP_CHAVE] = $tp->TP_CHAVE .'='. $tp->TP_DESCRICAO;
        }

        $tabela_padrao = TabelaPadrao::Where('TP_TABELA','tipo_projeto')->orderby('TP_CHAVE','ASC')->get();
        $tipo_combo = [];
        $tipo_combo[""] = "Todos";
        foreach($tabela_padrao as $tp) {
            $tipo_combo[$tp->TP_CHAVE] = $tp->TP_CHAVE .'='. $tp->TP_DESCRICAO;
        }

        $clientes = DB::table('tbl_clt as c')->select('c.*')
            ->where('clt_filial', Session::get('filial'))->orderBy('c.clt_nome_razao','ASC')->get();

        $cliente_combo = [];
        $cliente_combo[""] = "Todos";
        foreach($clientes as $cli) {
            $cliente_combo[$cli->clt_id] = $cli->clt_nome_razao;
        }

        if (Session::get('coordenador') || Session::get('cliente') || Session::get('status') || Session::get('tipo')) {
            $rel = DB::Table('tbl_equip as proj')->select('u.name as coordenador','f.filial_descricao as filial','c.clt_nome_razao as cliente', 'proj.equip_id as equip_id', 'proj.equip_nome as cod_projeto', 'proj.equip_desc as nome_projeto', 'tp1.TP_DESCRICAO as status', 'tp2.TP_DESCRICAO as tipo')
                ->join('tbl_clt as c', function ($join) {
                    $join->On('c.clt_id', '=', 'proj.equip_cliente')->On('c.clt_filial', '=', 'proj.equip_filial');
                })
                ->leftjoin('tabela_padrao as tp1', function ($join) {
                    $join->On('tp1.TP_CHAVE', '=', 'proj.equip_ativo')->where('tp1.TP_TABELA', '=', 'status_projeto');
                })
                ->leftjoin('tabela_padrao as tp2', function ($join) {
                    $join->On('tp2.TP_CHAVE', '=', 'proj.equip_tipo')->Where('tp2.TP_TABELA', '=', 'tipo_projeto');
                })
                ->leftjoin('user as u', 'u.id', '=', 'proj.equip_coordenador')
                ->join('tbl_filial as f','f.filial_cod','=','proj.equip_filial')
                ->where('proj.equip_filial', Session::get('filial'));

            if (Session::get('coordenador')) {
                $rel = $rel->wherein('proj.equip_coordenador', Session::get('coordenador'));
            }

            if (Session::get('cliente') != '') {
                $rel = $rel->where('proj.equip_cliente', Session::get('cliente'));
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

    public function buscarAjaxProjeto(Request $request)
    {
        $cliente_id = $request->cliente_id;
        $projetos = DB::connection('mysql')->table('tbl_equip as p')->select('p.*')
            ->where('equip_cliente', $cliente_id)->orderBy('p.equip_nome','ASC')->get();

        return json_encode($projetos);
    }

    public function access($id)
    {
        Session::put('projeto', $id);

        if(Gate::denies('acesso_projeto')) {
            return view('nao_autorizado');
        }

        $permissao = DB::Table('tbl_equip_user_assoc as eua')->select('u.name as name','eua.eua_id as id', 'u.id as user_id')
            ->join('user as u', function ($join) {
                $join->On('u.id', '=', 'eua.eua_user_id')->Where('eua.eua_equip_id', '=', Session::get('projeto'));
            })->OrderBy('u.name', 'ASC')->get();

        $projeto = Projeto::find($id);
        $projeto = $projeto->equip_nome . ' - ' .  $projeto->equip_desc;

        $usernotin = [];
        foreach($permissao as $per) {
            $usernotin[$per->user_id] = $per->user_id;
        }

        $usuarios = DB::Table('user as u')->select('u.name as name','u.id as id')->where('u.state','0')->whereNotIn('u.id',$usernotin)->OrderBy('u.name', 'ASC')->get();

        $usuarios_combo = [];
        foreach($usuarios as $usu) {
            $usuarios_combo[$usu->id] = $usu->name;
        }

        return view('cadastros.projeto.projetousuario' , [
            'usuarios' => $usuarios_combo,
            'permissao' => $permissao,
            'projeto' => $projeto,
            'projeto_id' => $id
        ]);
    }

    public function accessStore(Request $request)
    {
        $projetousuario = new ProjetoUsuario();
        $projetousuario->eua_equip_id = Input::get('projeto');
        $projetousuario->eua_user_id = Input::get('usuario');
        $projetousuario->save();

        return Redirect::route('projeto.acesso', [ 'id' => Input::get('projeto') ]);
    }

    public function accessDestroy($id,$projeto)
    {
        $projetousuario = ProjetoUsuario::find($id);
        $projetousuario->delete();

        Flash::success('Relacionamento entre Usuário vs Projeto excluído com sucesso!');

        return Redirect::route('projeto.acesso', [ 'id' => $projeto ]);
    }
}
