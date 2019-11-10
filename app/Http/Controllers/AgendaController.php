<?php

namespace App\Http\Controllers;

use App\Especialidade;
use App\Evento;
use App\Filial;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        //dd($request->consultor);
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

        $consultor = Usuario::where('state', '0')->orderby('name', 'ASC')->get();
        $consultor_array = [];
        $consultor_array[""] = "Todos";
        foreach ($consultor as $co) {
            $consultor_array[$co->id] = $co->name;
        }

        $anos = Evento::select(DB::raw('Year(date) as ano'))->groupby(DB::raw('Year(date) ASC'))->get();
        $ano_combo = [];
        foreach ($anos as $ano) {
            $ano_combo[$ano->ano] = $ano->ano;
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

        return view('agenda.listar', [
            'ano'               => $ano_combo,
            'ano_selecionado'   => empty($request->ano) ? Carbon::now()->format('Y') : $request->ano,
            'mes'               => $mes,
            'mes_selecionado'   => empty($request->mes) ? Carbon::now()->format('m') : $request->mes,
            'filiais'           => $filial_combo,
            'especialidades'    => $especialidade_combo,
            'consultores_combo' => $consultor_array,

            'filial'        => empty($request->filial) ? '' : $request->filial,
            'consultor'     => empty($request->consultor) ? (isset($request->filtrar) ? '' : auth()->user()->id) : $request->consultor,
            'especialidade' => empty($request->especialidade) ? '' : $request->especialidade,
        ]);
    }

    public function eventosJson(Request $request)
    {

        //dd($request->all());

        $consultor = [];
        if(isset($request->consultor)) {
            $consultor = array_filter($request->consultor);
        }

        $filial = [];
        if(isset($request->filial)) {
            $filial = array_filter($request->filial);
        }

        $especialidade = [];
        if(isset($request->especialidade)) {
            $especialidade  = array_filter($request->especialidade);
        }

        //dd($consultores, $filial, $especialidade);


        $eventos = DB::connection('mysql')
            ->table('evento')
            ->join('user', 'user.id', '=', 'evento.userid')
            ->leftJoin('tbl_clt', 'tbl_clt.clt_id', '=', 'evento.client')
            ->where('evento.date', '>=', $request->start)
            ->where('evento.date', '<=', $request->end)
            //->whereRaw("evento.date >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-%d')")
            ->select(
                'evento.id',
                'evento.description',
                'evento.date',
                'evento.starthour',
                'evento.endhour',
                'user.name',
                'tbl_clt.clt_nome_razao'
            );

        /*
         * FILTROS
         *
         * */

        if(!empty($consultor)) {
            $eventos->whereIn('user.id', $consultor);
        }
        if(!empty($filial)) {
            $eventos->whereIn('user.filialmestre', $filial);
        }
        if(!empty($especialidade)) {
            $eventos->whereIn('user.especialidade_colaborador', $especialidade);
        }



        $result = [];
        foreach ($eventos->get() as $e) {

            $evento = Evento::find($e->id);

            $result[] = [
                //'title' => " até ".substr($e->endhour, 0, 5)." - ".$e->name."\n".$evento->typeDescricao(),
                'title' => " até ".substr($e->endhour, 0, 5)." - ".$evento->locationDescricao()."\n".$e->name."\n".$evento->typeDescricao(),
                'url'   => route('evento.visualizar', $e->id),
                'start' => $e->date.'T'.$e->starthour,
                'end'   => $e->date.'T'.$e->endhour
            ];
        }

        $result_json = json_encode($result);
        return $result_json;
    }
}
