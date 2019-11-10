<?php

namespace App\Http\Controllers\AgendaCorporativa;

use App\Evento;
use App\Filial;
use App\TabelaPadrao;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ListarController extends Controller
{
    public function index()
    {
        if(Gate::denies('agenda_visualizar')) {
            return view('nao_autorizado');
        }

        // filtro
        $data = Carbon::now()->format('d/m/Y');

        $tipos_agenda   = TabelaPadrao::Where('TP_TABELA', 'tipo_agenda')->orderby('TP_CHAVE','ASC')->get()->pluck('TP_DESCRICAO','TP_CHAVE');

        $usuario_filial = DB::table('tbl_usuario_filial')->where('usuario', auth()->user()->login)->get()->pluck('filial')->toArray();
        $filiais = Filial::whereIn('filial_cod', $usuario_filial)
            ->orderBy('filial_descricao', 'ASC')
            ->get();


        $filiais_combo = [];
        $filiais_combo[""] = "Todas";
        foreach($filiais as $fil) {
            $filiais_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        $torres = TabelaPadrao::Where('TP_TABELA', 'torre')->orderby('TP_CHAVE','ASC')->get();
        $torres_combo = [];
        $torres_combo[""] = "Todas";
        foreach($torres as $tor) {
            $torres_combo[$tor->TP_CHAVE] = $tor->TP_DESCRICAO;
        }

        $consultor = Usuario::orderby('name', 'ASC')->where('rel_capacidade_agenda', 'S')->get();
        $consultores_combo = [];
        $consultores_combo[""] = "Todos";
        foreach ($consultor as $co) {
            $consultores_combo[$co->id] = $co->name;
        }

        $combo_semana = [
            '1'     => '1 Semana',
            '2'     => '2 Semanas',
            '3'     => '3 Semanas',
            '4'     => '4 Semanas',
            '8'     => '8 Semanas',
            '24'    => '24 Semanas'
        ];

        return view('agenda-corporativa.listar', compact(
            'data',
            'tipos_agenda',
            'torres_combo',
            'filiais_combo',
            'consultores_combo',
            'combo_semana'
        ));
    }

    public function pesquisar(Request $request)
    {
        //dd($request->all());

        $consultores    = ($request->consultor) ? $request->consultor : [];
        $consultores    = array_filter($consultores);

        $data           = Carbon::createFromFormat('d/m/Y', $request->data);
        $inicio_semana  = $data->startOfWeek();

        // mostrar semana
        $semana = $request->semana;

        // direcao
        if(!empty($request->semana_direcao)) {
            $inicio_semana = Carbon::createFromFormat('d/m/Y', $request->primeira_data_tabela);
            if($request->semana_direcao == "anterior") {
                $inicio_semana = $inicio_semana->addDay(-7);
            } elseif($request->semana_direcao == "seguinte") {
                $inicio_semana = $inicio_semana->addDay(7);
            }
        }

        // montagem das datas
        $dt = $inicio_semana;
        $datas = [];
        for ($i = 1; $i <= $semana * 7; $i++) {
            $dia_semana = $dt->format('N');
            $datas[$dt->format('Y-m-d')] = [
                'formatado'     => $dt->format('d/m/Y'),
                'dia_semana'    => $this->diaSemana($dia_semana)
            ];
            $dt->addDay();
        }

        // todos os registros da tabela
        $dados_tabela = [];


        // usuarios com permissao por filial
        $query_usuarios_permitidos = Usuario::select(DB::raw('user.id'))
            ->join('tbl_usuario_filial', 'tbl_usuario_filial.usuario', '=', 'user.login')
            ->where('rel_capacidade_agenda', 'S')
            ->groupBy('user.id');
        if(!empty($request->filial)) {
            $query_usuarios_permitidos->where('tbl_usuario_filial.filial', $request->filial);
        }
        $usarios_ids = $query_usuarios_permitidos->get();


        // base
        $query = Usuario::whereIn('user.id', $usarios_ids)
            ->where('rel_capacidade_agenda', 'S')
            ->orderBy('name');

        // torre
        if($request->torre <> "") {
            $usuarios_ids_torre = Usuario::where('torre', $request->torre)
                ->where('rel_capacidade_agenda', 'S')
                ->pluck('id');
            $query->whereIn('user.id', $usuarios_ids_torre);
        }

        // consultores
        if($consultores) {
            $query->whereIn('user.id', $consultores);
        }
        // resultado
        $usuarios = $query->get();

        foreach ($usuarios as $usuario) {

            // coluna consultor - fixo
            $consultor = $usuario->name;
            if($usuario->filial) {
                $consultor .= ' - '.$usuario->filial->filial_desc_reduz;
            }
            if($usuario->especialidade) {
                $consultor .= "<br /><br />Especialidade: ";
                $consultor .= "<br /><br />".$usuario->especialidade->descricao;
            }
            $consultor .= '<br /><br /><br /><br />';
            $registro = [
                'consultor'     => $consultor,
                'consultor_id'  => $usuario->id,
                'filial_cor'    => $usuario->filial->filial_cor_agenda
            ];

            // colunas de datas dinamicas
            $dados_data = [];
            foreach ($datas as $data_key => $data) {

                $value = '<div style="width:150px; white-space: normal">';
                $query_eventos = Evento::where('userid', $usuario->id)
                    ->where('date', $data_key);

                // tipo de agenda
                if(!empty($request->tipo_agenda)) {
                    $query_eventos->where('type', $request->tipo_agenda);
                }

                // cliente
                if(!empty($request->cliente)) {
                    $query_eventos->where('client', $request->cliente);
                }
                // projeto
                if(!empty($request->projeto)) {
                    $query_eventos->where('project', $request->projeto);
                }

                // resultado
                $eventos = $query_eventos->get();
                foreach ($eventos as $evento) {
                    $value .= '
                        <a href="#"
                           data-fancybox
                           data-type="ajax"
                           data-hideOnContentClick="true"
                           data-showCloseButton="true"
                           data-src="agenda-corporativa/evento/editar/'.$evento->id.'"
                        >
                    ';

                    $color = '#000';
                    if($evento->tipo) {
                        if(!empty($evento->tipo->TP_PARAMETRO)) {
                            $color = $evento->tipo->TP_PARAMETRO;
                        }
                    }
                    $style = 'style="color:'.$color.'"';

                    if($evento->cliente) {
                        $value .= "<br /><strong $style>".$evento->cliente->clt_nome_razao."</strong>";
                    } else {
                        if($evento->tipo) {
                            $value .= "<br /><strong $style>".$evento->tipo->TP_DESCRICAO."</strong>";
                        } 
                    }
                    $value .= "<br />".$evento->starthour.' até '.$evento->endhour;
                    $value .= "<br />".utf8_decode($evento->description);
                    //$value .= "<br />".$evento->typeDescricao();
                    //$value .= "<br />".$evento->locationDescricao();
                    $value .= "<br />";
                    $value .= "</a><br /><br />";
                }
                $value .= '</div>';
                $dados_data[$data_key] = $value;
            }

            // add ao registro final
            $dados_tabela[] = array_merge($registro, $dados_data);
        }

        //dd($dados_tabela);

        return view('agenda-corporativa.tabela', compact(
            'datas',
            'dados_tabela'
        ));
    }

    public function diaSemana($dia)
    {
        switch ($dia) {
            case "1":
                $retorno = 'Segunda';
                break;
            case "2":
                $retorno = 'Terça';
                break;
            case "3":
                $retorno = 'Quarta';
                break;
            case "4":
                $retorno = 'Quinta';
                break;
            case "5":
                $retorno = 'Sexta';
                break;
            case "6":
                $retorno = 'Sábado';
                break;
            case "7":
                $retorno = 'Domingo';
                break;
            default:
                $retorno = $dia;
        }
        return $retorno;
    }

    public function buscarAjaxProjeto(Request $request)
    {
        $cliente_id = $request->cliente_id;
        $projetos = DB::connection('mysql')
            ->table('tbl_equip as p')
            ->select('p.*')
            ->where('equip_cliente', $cliente_id)
            ->where('equip_ativo', '0')
            ->orderBy('p.equip_desc','ASC')
            ->get();

        return json_encode($projetos);
    }
}
