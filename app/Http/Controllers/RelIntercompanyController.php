<?php

namespace App\Http\Controllers;

use App\Especialidade;
use App\Evento;

use App\Filial;
use App\OS;
use App\Parametro;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class RelIntercompanyController extends Controller
{
    public function index(Request $request)
    {
        if(Gate::denies('rel_intercompany')) {
            return view('nao_autorizado');
        }

        $anos = OS::select(DB::raw('Year(os_data_entrada) as ano'))->groupby(DB::raw('Year(os_data_entrada) ASC'))->get();
        $ano_combo = [];
        foreach($anos as $ano) {
            $ano_combo[$ano->ano] = $ano->ano;
        }

        $filiais = Filial::orderby('filial_descricao','ASC')->get();
        $filial_combo = [];
        //$filial_combo[""] = "Todas";
        foreach($filiais as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        $especialidades = Especialidade::orderby('descricao','ASC')->get();
        $especialidade_combo = [];
        $especialidade_combo[""] = "Todas";
        foreach($especialidades as $esp) {
            $especialidade_combo[$esp->id] = $esp->descricao;
        }

        $consultor = Usuario::where('state','0')->orderby('name','ASC')->get();
        $consultor_array = [];
        $consultor_array[""] = "Todos";
        foreach($consultor as $co) {
            $consultor_array[$co->id] = $co->name;
        }

        if($request->filial) {
            $filial = $request->filial;
        } else {
            $filial = '1';
        }

        if(!Empty($request->especialidade[0])) {
            $especialidade = $request->especialidade;
        } else {
            $especialidade = '';
        }

        if(!Empty($request->consultor[0])) {
            $consultor = $request->consultor;
        } else {
            $consultor = '';
        }

        $mes["01"] = 'Janeiro';
        $mes["02"] = 'Fevereiro';
        $mes["03"] = 'Março';
        $mes["04"] = 'Abril';
        $mes["05"] = 'Maio';
        $mes["06"] = 'Junho';
        $mes["07"] = 'Julho';
        $mes["08"] = 'Agosto';
        $mes["09"] = 'Setembro';
        $mes["10"] = 'Outubro';
        $mes["11"] = 'Novembro';
        $mes["12"] = 'Dezembro';

        if(!$request->ano_de) {
            $ano_de = date('Y', strtotime("-12 month", strtotime(date('Y-m-d'))));
        } else {
            $ano_de = $request->ano_de;
        }

        if(!$request->ano_ate) {
            $ano_ate = date('Y', strtotime("-0 month", strtotime(date('Y-m-d'))));
        } else {
            $ano_ate = $request->ano_ate;
        }

        if(!$request->mes_de) {
            $mes_de = date('m', strtotime("-12 month", strtotime(date('Y-m-d'))));
        } else {
            $mes_de = $request->mes_de;
        }

        if(!$request->mes_ate) {
            $mes_ate = date('m', strtotime("-0 month", strtotime(date('Y-m-d'))));
        } else {
            $mes_ate = $request->mes_ate;
        }

        $date1 = Carbon::createFromFormat('Y-m-d',$ano_de.'-'.$mes_de.'-01');
        $date2 = Carbon::createFromFormat('Y-m-d',$ano_ate.'-'.$mes_ate.'-01')->endOfMonth();
        $date3 = Carbon::createFromFormat('Y-m-d',$ano_de.'-'.$mes_de.'-01');

        $consultores = DB::connection('mysql')->Table('user as u')->select('u.id','u.name as consultor','f.filial_descricao as unidade','es.descricao as especialidade')
            ->leftjoin('tbl_especialidade as es','es.id','=','u.especialidade_colaborador')
            ->leftjoin('tbl_filial as f','f.filial_cod','=','u.filialmestre')
            ->where('state','0');

        //if($filial!='') {
        //    $consultores = $consultores->where('u.filialmestre',$filial);
        //}

        if($especialidade!='') {
            $consultores = $consultores->wherein('u.especialidade_colaborador',$especialidade);
        }

        if($consultor!='') {
            $consultores = $consultores->wherein('u.id',$consultor);
        }

        $consultores = $consultores->groupby('u.id','u.name','f.filial_descricao','es.descricao')
            ->orderby('u.name','ASC')->get();

        //--------------------------------------Coleta os valores de RED

        $consultores_RED = DB::connection('mysql')->Table('tbl_excecao as e')->select('u.id',DB::raw("DATE_FORMAT(e.exc_data,'%m-%Y') as comp"),DB::raw("sum(exc_pedagio) as pedagio"),DB::raw("sum(exc_estacionamento) as estacionamento"),DB::raw("sum(exc_despesas) as despesas"),DB::raw("sum(exc_km * tipokm_valor) as km"))
            ->join('user as u','u.login','=','e.exc_usuario')
            ->join('tbl_tipokm as tpkm', function ($join) {
                $join->On('u.km', '=', 'tpkm.tipokm_id')->On('u.filialmestre', '=', 'tpkm.tipokm_filial');
            })
            ->leftjoin('tbl_especialidade as es','es.id','=','u.especialidade_colaborador')
            ->where('state','0')
            ->where('u.filialmestre','<>',$filial)
            ->where('e.exc_data','>=',$date1->format('Y-m-d'))
            ->where('e.exc_data','<=',$date2->format('Y-m-d'));

        //if($filial!='') {
            $consultores_RED = $consultores_RED->where('e.exc_filial','=',$filial);
        //}

        if($especialidade!='') {
            $consultores_RED = $consultores_RED->wherein('u.especialidade_colaborador', $especialidade);
        }

        if($consultor!='') {
            $consultores_RED = $consultores_RED->wherein('u.id',$consultor);
        }

        $consultores_RED = $consultores_RED->groupby('u.id',DB::raw("DATE_FORMAT(e.exc_data,'%m-%Y')"))->get();

        $RED_combo = [];
        foreach($consultores_RED as $c) {
            $RED_combo[$c->id.$c->comp] = $c->pedagio + $c->estacionamento + $c->despesas + $c->km;
        }

        //--------------------------------------Coleta os valores de OS

        $consultores_OS = DB::connection('mysql')->Table('tbl_os as o')->select('u.id',DB::raw("DATE_FORMAT(o.os_data_entrada,'%m-%Y') as comp"),DB::raw("sum((TIME_TO_SEC(o.os_total1)/60)/60) as horas"))
            ->join('user as u','u.login','=','o.rel_user_login')
            ->leftjoin('tbl_especialidade as es','es.id','=','u.especialidade_colaborador')
            ->where('state','0')
            ->where('u.filialmestre','<>',$filial)
            ->where('o.os_data_entrada','>=',$date1->format('Y-m-d'))
            ->where('o.os_data_entrada','<=',$date2->format('Y-m-d'));

        //if($filial!='') {
            $consultores_OS = $consultores_OS->where('o.os_filial',$filial);
        //}

        if($especialidade!='') {
            $consultores_OS = $consultores_OS->wherein('u.especialidade_colaborador', $especialidade);
        }

        if($consultor!='') {
            $consultores_OS = $consultores_OS->wherein('u.id',$consultor);
        }

        $consultores_OS = $consultores_OS->groupby('u.id',DB::raw("DATE_FORMAT(o.os_data_entrada,'%m-%Y')"))->get();

        $vl_hora_intercompany = Parametro::where('nome','valor_intercompany')->first()->valor;

        $OS_combo = [];
        foreach($consultores_OS as $c) {
            $OS_combo[$c->id.$c->comp] = $c->horas * floatval($vl_hora_intercompany);
        }

        $diff = $date1->diffInMonths($date2,false);

        for ($i = 0; $i <= $diff ; ++$i) {
            if($i>0) {
                $date4 = $date3->addMonth(1)->format('m-Y');
            } else {
                $date4 = $date3->format('m-Y');
            }

            $dados[] = array($date4, 0, 0); //os dois ultimos é para despesas de exceção e OS

            $cabecalho[] = array($date4,0);
        }

        foreach($consultores as $c) {
            $rel[] = array($c->id, $c->consultor, $c->unidade, $c->especialidade, $dados);
        }

        if(count($consultores) == 0) {
            $rel = [];
        }

        foreach($rel as $key_rel => $r) {
            foreach($r[4] as $key => $d) {
                $pesquisar = $r[0] . $d[0];
                if (isset($RED_combo[$pesquisar])) {
                    $d[1] = $RED_combo[$pesquisar];
                }
                $r[4][$key] = $d;
            }
            $rel[$key_rel] = $r;
        }

        foreach($rel as $key_rel => $r) {
            foreach($r[4] as $key => $d) {
                $pesquisar = $r[0] . $d[0];
                if (isset($OS_combo[$pesquisar])) {
                    $d[2] = $OS_combo[$pesquisar];
                }
                $r[4][$key] = $d;
            }
            $rel[$key_rel] = $r;
        }

        $i=0;
        $y=0;
        $aux=0;
        foreach($rel as $r) {
            $i=0;
            foreach ($cabecalho as $key => $c) {
                $aux = $i++;
                $c[1] += $rel[$y][4][$aux][1] + $rel[$y][4][$aux][2];
                $cabecalho[$key] = $c;
            }
            $y++;
        }

        return view('relatorios.intercompany.listar', [
            'ano' => $ano_combo,
            'mes' => $mes,
            'ano_de' => $ano_de,
            'ano_ate' => $ano_ate,
            'mes_de' => $mes_de,
            'mes_ate' => $mes_ate,
            'rel' => $rel,
            'cabecalho' => $cabecalho,
            'filiais' => $filial_combo,
            'especialidades' => $especialidade_combo,
            'filial' => $filial,
            'especialidade' => $request->especialidade,
            'consultores_combo' => $consultor_array,
            'consultor' => $consultor
        ]);
    }
}
