<?php

namespace App\Http\Controllers;

use App\Especialidade;
use App\Evento;

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

class RelCAController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::denies('rel_capacidade_agenda')) {
            return view('nao_autorizado');
        }

        $anos = Evento::select(DB::raw('Year(date) as ano'))->groupby(DB::raw('Year(date) ASC'))->get();
        $ano_combo = [];
        foreach ($anos as $ano) {
            $ano_combo[$ano->ano] = $ano->ano;
        }

        $filiais = Filial::orderby('filial_descricao', 'ASC')->get();
        $filial_combo = [];
        $filial_combo[""] = "Todas";
        foreach ($filiais as $fil) {
            $filial_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        $especialidades = Especialidade::orderby('descricao', 'ASC')->get();
        $especialidade_combo = [];
        $especialidade_combo[""] = "Todas";
        foreach ($especialidades as $esp) {
            $especialidade_combo[$esp->id] = $esp->descricao;
        }

        $consultor = Usuario::where('state', '0')->where('rel_capacidade_agenda', '<>', 'N')->orderby('name', 'ASC')->get();
        $consultor_array = [];
        $consultor_array[""] = "Todos";
        foreach ($consultor as $co) {
            $consultor_array[$co->id] = $co->name;
        }

        if (!Empty($request->filial[0])) {
            $filial = $request->filial;
        } else {
            $filial = '';
        }

        if (!Empty($request->especialidade[0])) {
            $especialidade = $request->especialidade;
        } else {
            $especialidade = '';
        }

        if (!Empty($request->consultor[0])) {
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

        if (!$request->ano_de) {
            $ano_de = date('Y', strtotime("-0 month", strtotime(date('Y-m-d'))));
        } else {
            $ano_de = $request->ano_de;
        }

        if (!$request->ano_ate) {
            $ano_ate = date('Y', strtotime("+12 month", strtotime(date('Y-m-d'))));
        } else {
            $ano_ate = $request->ano_ate;
        }

        if (!$request->mes_de) {
            $mes_de = date('m', strtotime("-0 month", strtotime(date('Y-m-d'))));
        } else {
            $mes_de = $request->mes_de;
        }

        if (!$request->mes_ate) {
            $mes_ate = date('m', strtotime("+12 month", strtotime(date('Y-m-d'))));
        } else {
            $mes_ate = $request->mes_ate;
        }

        $date1 = Carbon::createFromFormat('Y-m-d', $ano_de . '-' . $mes_de . '-01');
        $date2 = Carbon::createFromFormat('Y-m-d', $ano_ate . '-' . $mes_ate . '-01')->endOfMonth();
        $date3 = Carbon::createFromFormat('Y-m-d', $ano_de . '-' . $mes_de . '-01');

        //dd($date1,$date2);

        $consultores = DB::connection('mysql')
            ->table('evento as e')
            ->select('u.id', 'u.name as consultor', 'f.filial_descricao as unidade', 'es.descricao as especialidade', 'u.filialmestre', 'u.especialidade_colaborador')
            ->join('user as u', 'u.id', '=', 'e.userid')
            ->leftjoin('tbl_especialidade as es', 'es.id', '=', 'u.especialidade_colaborador')
            ->leftjoin('tbl_filial as f', 'f.filial_cod', '=', 'u.filialmestre')
            ->where('state', '0')
            ->where('rel_capacidade_agenda', '<>', 'N')
            ->where('e.date', '>=', $date1->format('Y-m-d'))
            ->where('e.date', '<=', $date2->format('Y-m-d'));

        if ($filial != '') {
            $consultores = $consultores->wherein('u.filialmestre', $filial);
        }

        if ($especialidade != '') {
            $consultores = $consultores->wherein('u.especialidade_colaborador', $especialidade);
        }

        if ($consultor != '') {
            $consultores = $consultores->wherein('u.id', $consultor);
        }

        $consultores = $consultores->groupby('u.id', 'u.name', 'f.filial_descricao', 'es.descricao', 'u.filialmestre', 'u.especialidade_colaborador')
            ->orderby('u.name', 'ASC')->get();

        $consultores_dias = DB::connection('mysql')
            ->table('evento as e')
            ->select('u.id', DB::raw("DATE_FORMAT(e.date,'%m-%Y') as comp"), DB::raw("count(DATE_FORMAT(e.date,'%m-%Y')) as dias"))
            ->join('user as u', 'u.id', '=', 'e.userid')
            ->leftjoin('tbl_especialidade as es', 'es.id', '=', 'u.especialidade_colaborador')
            ->where('state', '0')
            ->where('rel_capacidade_agenda', '<>', 'N')
            ->where('e.date', '>=', $date1->format('Y-m-d'))
            ->where('e.date', '<=', $date2->format('Y-m-d'));

        if ($filial != '') {
            $consultores_dias = $consultores_dias->wherein('u.filialmestre', $filial);
        }

        if ($especialidade != '') {
            $consultores_dias = $consultores_dias->wherein('u.especialidade_colaborador', $especialidade);
        }

        if ($consultor != '') {
            $consultores_dias = $consultores_dias->wherein('u.id', $consultor);
        }

        $consultores_dias = $consultores_dias->groupby('u.id', DB::raw("DATE_FORMAT(e.date,'%m-%Y')"))->get();

        $consultores_combo = [];
        foreach ($consultores_dias as $c) {
            $consultores_combo[$c->id . $c->comp] = $c->dias;
        }

        $diff = $date1->diffInMonths($date2, false);

        for ($i = 0; $i <= $diff; ++$i) {
            if ($i > 0) {
                $date4 = $date3->addMonth(1)->format('m-Y');
            } else {
                $date4 = $date3->format('m-Y');
            }
            $data_ini = $date3->format('Y-m-d');
            $data_fim = $date3->format('Y-m-t');

            $dados[] = array($date4, $data_ini, $data_fim, $this->CalcDiasUteis($data_ini, $data_fim), 0, 0, 0);

            $cabecalho[] = array($date4, 0);
        }

        foreach ($consultores as $c) {
            $rel[] = array($c->id, $c->consultor, $c->unidade, $dados, $c->especialidade, $c->filialmestre, $c->especialidade_colaborador);
        }

        if (count($consultores) == 0) {
            $rel = [];
        } else {

            foreach ($rel as $key_rel => $r) {
                foreach ($r[3] as $key => $d) {
                    $pesquisar = $r[0] . $d[0];
                    if (isset($consultores_combo[$pesquisar])) {
                        $d[4] = $consultores_combo[$pesquisar];
                    }

                    $d[5] = ($d[4] / $d[3]) * 100;
                    if ($d[5] > 100) {
                        $d[5] = 100;
                    }

                    $d[6] = $d[3] - $d[4];
                    if ($d[6] < 0) {
                        $d[6] = 0;
                    }

                    $r[3][$key] = $d;
                }
                $rel[$key_rel] = $r;
            }

            $i = 0;
            $y = 0;
            foreach ($rel as $r) {
                $i = 0;
                foreach ($cabecalho as $key => $c) {
                    $c[1] += $rel[$y][3][$i++][5];
                    $cabecalho[$key] = $c;
                }
                $y++;
            }

            foreach ($cabecalho as $key => $c) {
                $c[1] = $c[1] / count($rel);
                $cabecalho[$key] = $c;
            }
        }

        return view('relatorios.capacidade.listar', [
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

    public function CalcDiasUteis($data1,$data2) {
        $data1 = Carbon::createFromFormat('Y-m-d',$data1);
        $data2 = Carbon::createFromFormat('Y-m-d',$data2);

        $dias = $data1->diffInDays($data2,false) + 1;
        $conta = 0;

        for ($y = 1; $y <= $dias ; ++$y) {
            if ($y > 1) {
                $data3 = $data1->addDay(1)->format('Y-m-d');
            } else {
                $data3 = $data1->format('Y-m-d');
            }
            if(!(date('w', strtotime($data3)) == '0' || date('w', strtotime($data3)) == '6')) {
                $conta++;
            }
        }
        return $conta;
    }
}
