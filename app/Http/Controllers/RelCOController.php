<?php

namespace App\Http\Controllers;

set_time_limit(0);

use App\Filial;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class RelCOController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('rel_OS')) {
            return view('nao_autorizado');
        }

        $consultor = Usuario::orderby('name', 'ASC')->get();
        $consultor_array = [];
        $consultor_array[""] = "Todos";
        foreach ($consultor as $co) {
            $consultor_array[$co->id] = $co->name;
        }

        $filiais = Filial::orderby('filial_descricao','ASC')->get();
        $filial_combo = [];
        $filial_combo[""] = "Todas";
        foreach($filiais as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        if ($request->os) {
            $os = $request->os;
        } else {
            $os = '';
        }

        if(!Empty($request->consultor[0])) {
            $consultor = $request->consultor;
        } else {
            $consultor = '';
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

        if($request->projeto_sel) {
            Session::put('projeto', $request->projeto_sel);
        } else {
            Session::put('projeto', '');
        }

        if ($request->texto) {
            $texto = $request->texto;
        } else {
            $texto = '';
        }

        if ($request->datade) {
            $datade = $request->datade;
        } else {
            $datade = '';
        }

        if ($request->dataate) {
            $dataate = $request->dataate;
        } else {
            $dataate = '';
        }

        if ($request->faturada) {
            $faturada = $request->faturada;
        } else {
            $faturada = '';
        }

        $faturada_combo["T"] = 'Todas';
        $faturada_combo["S"] = 'Sim';
        $faturada_combo["N"] = 'Não';

        if ($consultor != '' || $filial != '' || $texto != '' || $datade != '' || $dataate != '' || $os != '' || $faturada != '') {
            $rel = DB::connection('mysql')->Table('tbl_os_atv as a')->select('o.os_id as id_os','f.filial_descricao as filial','c.clt_nome_razao as cliente', 'u.name as consultor', 'o.os_data_entrada as data', 'a.trabalhos as texto', 'atv.atv_desc as atividade', 'a.horas as horas_atividade', 'proj.equip_nome as cod_projeto', 'proj.equip_desc as nome_projeto', 'o.os_hinicio', 'o.os_hfim', 'o.os_almoinicio', 'o.os_almofim', 'o.os_total1', 'o.os_com_traslado', 'c.clt_translado')
                ->join('tbl_os as o', 'o.os_id', '=', 'a.os_id')
                ->join('tbl_atv as atv', 'atv.atv_id', '=', 'a.atv_id')
                ->join('tbl_equip as proj', 'proj.equip_id', '=', 'atv.atv_projeto')
                ->join('tbl_clt as c', function ($join) {
                    $join->On('c.clt_id', '=', 'o.rel_clt_id')->On('c.clt_filial', '=', 'o.os_filial');
                })
                ->join('user as u', 'u.login', '=', 'o.rel_user_login')
                ->join('tbl_filial as f','f.filial_cod','=','o.os_filial');

            if ($os != '') {
                $rel = $rel->where('o.os_id', $os);
            }

            if ($consultor != '') {
                $rel = $rel->wherein('u.id', $consultor);
            }

            if ($filial != '') {
                $rel = $rel->where('o.os_filial', $filial);
            }

            if ($cliente != '') {
                $rel = $rel->where('o.rel_clt_id', $cliente);
            }

            if(!empty(Session::get('projeto')) && Session::get('projeto') != '') {
                $rel = $rel->where('o.rel_equip_id','=',Session::get('projeto'));
            }

            if ($texto != '') {
                $rel = $rel->where('a.trabalhos', 'like', '%'.$texto.'%');
            }

            if ($datade != '') {
                $rel = $rel->where('o.os_data_entrada', '>=',date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', $datade))));
            }

            if ($dataate != '') {
                $rel = $rel->where('o.os_data_entrada', '<=',date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', $dataate))));
            }

            if ($faturada == 'S') {
                $rel = $rel->where('o.os_faturada', $faturada);
            }

            if ($faturada == 'N') {
                $rel = $rel->where('o.os_faturada', $faturada);
            }

            $rel = $rel->orderBy('o.os_data_entrada','ASC')->get();
        } else {
            $rel = [];
        }

        return view('relatorios.os.listar', [
            'rel' => $rel,
            'os' => $os,
            'consultores_combo' => $consultor_array,
            'consultor' => $consultor,
            'filiais_combo' => $filial_combo,
            'filial' => $filial,
            'cliente' => $cliente,
            'texto' => $texto,
            'datade' => $datade,
            'dataate' => $dataate,
            'faturada_combo' => $faturada_combo,
            'faturada' => $faturada
        ]);
    }

    public function buscarAjaxCliente(Request $request)
    {
        $filial_id = $request->filial_id;
        $clientes = DB::connection('mysql')->table('tbl_clt as c')->select('c.*')
            ->where('clt_filial', $filial_id)->orderBy('c.clt_nome_razao','ASC')->get();

        return json_encode($clientes);
    }

    public function buscarAjaxProjeto(Request $request)
    {
        $cliente_id = $request->cliente_id;
        $projetos = DB::connection('mysql')->table('tbl_equip as p')->select('p.*')
            ->where('equip_cliente', $cliente_id)->orderBy('p.equip_desc','ASC')->get();

        return json_encode($projetos);
    }
}
