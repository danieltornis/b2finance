<?php

namespace App\Http\Controllers;

set_time_limit(0);

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;


class RelGeralController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('rel_Geral')) {
            return view('nao_autorizado');
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

        if ($datade != '' || $dataate != '') {
            $rel = DB::connection('mysql')->Table('tbl_os as o')->select(
                'o.os_id as id_os',
                'os_data_entrada as data',
                'f.filial_descricao as filial',
                'u.name as consultor',
                'u.login',
                'u.email',
                'u.state as status_usuario',
                'fm.filial_descricao as filialmestre',
                'e.descricao as especialidade',
                'u.custo_colaborador',
                'u.valor_hora',
                'tp1.TP_DESCRICAO as torre',
                'u.regime as regime',
                'c.clt_nome_razao as cliente',
                'clt_cidade',
                'clt_estado',
                'clt_cpf_cnpj',
                'clt_email',
                'clt_translado',
                'clt_pedagio',
                'clt_km',
                'clt_fatorkm',
                'clt_fator_imposto',
                'clt_valor_refeicao',
                'clt_cliente_interno',
                'ger.name as gerente',
                'execv.name as executivov',
                'proj.equip_nome as cod_projeto',
                'proj.equip_desc as nome_projeto',
                'tp2.TP_DESCRICAO as equip_ativo',
                'tipoh_nome',
                'tipoh_valor',
                'equip_rd',
                'tp.descricao as equip_tipo',
                'proj.equip_h_analista',
                'proj.equip_h_coordenador',
                'coord.name as coordenador',
                'proj.equip_reembolso_refeic',
                'proj.equip_valor_refeicao',
                'equip_service_desk',
                'os_hinicio',
                'os_hfim',
                'os_almoinicio',
                'os_almofim',
                'os_htrab',
                'os_halmo',
                'os_total1',
                'os_com_traslado',
                'os_faturada',
                'os_status',
                'os_reembolso',
                'os_home_office',
                'os_valor_hora',
                'os_valor_refeicao',
                'os_valor_pedagio',
                'os_qtd_km',
                'os_valor_km',
                'os_data_exec_trab',
                'os_data_inclusao')
                ->join('tbl_equip as proj', 'proj.equip_id', '=', 'o.rel_equip_id')
                ->join('tbl_clt as c', function ($join) {
                    $join->On('c.clt_id', '=', 'o.rel_clt_id')->On('c.clt_filial', '=', 'o.os_filial');
                })
                ->join('user as u', 'u.login', '=', 'o.rel_user_login')
                ->join('tbl_filial as f','f.filial_cod','=','o.os_filial')
                ->join('tbl_tipo_projeto as tp','tp.id','=','equip_tipo')
                ->join('tbl_tipoh as tph','tipoh_id','=','rel_tipoh_id')
                ->leftJoin('user as ger', 'ger.id', '=', 'clt_gerente')
                ->leftJoin('user as execv', 'execv.id', '=', 'clt_execvendas')
                ->leftJoin('user as coord', 'coord.id', '=', 'equip_coordenador')
                ->leftJoin('tbl_especialidade as e', 'e.id', '=', 'u.especialidade_colaborador')
                ->leftJoin('tbl_filial as fm', 'fm.filial_cod', '=', 'u.filialmestre')
                ->leftJoin('tabela_padrao as tp1', function ($join2) {
                    $join2->On('tp1.TP_CHAVE', '=', 'u.torre')->Where('tp1.TP_TABELA', '=', 'torre');
                })
                ->leftJoin('tabela_padrao as tp2', function ($join3) {
                    $join3->On('tp2.TP_CHAVE', '=', 'proj.equip_ativo')->Where('tp2.TP_TABELA', '=', 'status_projeto');
                });

            if ($datade != '') {
                $rel = $rel->where('o.os_data_entrada', '>=',date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', $datade))));
            }

            if ($dataate != '') {
                $rel = $rel->where('o.os_data_entrada', '<=',date('Y-m-d', strtotime(Carbon::createFromFormat('d/m/Y', $dataate))));
            }

            $rel = $rel->orderBy('o.os_data_entrada','ASC')->get();
        } else {
            $rel = [];
        }

        return view('relatorios.geral.listar', [
            'rel' => $rel,
            'datade' => $datade,
            'dataate' => $dataate
        ]);
    }
}
