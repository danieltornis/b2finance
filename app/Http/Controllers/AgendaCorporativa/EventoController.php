<?php

namespace App\Http\Controllers\AgendaCorporativa;

use App\Evento;
use App\Filial;
use App\Mail\Agenda;
use App\Projeto;
use App\TabelaPadrao;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function novo($consultor_id, $data)
    {
        if(Gate::denies('agenda_incluir')) {
            return view('nao_autorizado');
        }
        return $this->form(null, $consultor_id, $data);
    }

    public function editar($evento_id)
    {
        $tem_permissao = false;
        if(Gate::allows('agenda_alterar')) {
            $evento = Evento::find($evento_id);
            $login_inc = $evento->login_inc;
            if(Gate::allows('agenda_alterar_todas')) {
                if(auth()->user()->login == $login_inc) {
                    $tem_permissao = true;
                } else {
                    if(Gate::allows('agenda_alterar')) {
                        $tem_permissao = true;
                    } else {
                        $tem_permissao = false;
                    }
                }
            } else {
                if(auth()->user()->login == $login_inc) {
                    $tem_permissao = true;
                } else {
                    $tem_permissao = false;
                }
            }
        } else {
            $tem_permissao = false;
        }

        if(!$tem_permissao) {
            return $this->visualizar($evento_id);
        }

        return $this->form($evento_id);
    }


    public function visualizar($evento_id)
    {
        $evento = Evento::find($evento_id);
        return view('agenda-corporativa.evento.visualizar', compact(
            'evento'
        ));
    }

    private function form($evento_id = null, $consultor_id = null, $data = null)
    {
        $evento = null;
        if($evento_id) {
            $evento = Evento::find($evento_id);
            $consultor = $evento->consultor;
        }

        if($consultor_id) {
            $consultor = Usuario::find($consultor_id);
        }

        if($data) {
            $data = Carbon::createFromFormat('Y-m-d', $data);
        }

        $tipos_agenda   = TabelaPadrao::Where('TP_TABELA', 'tipo_agenda')->orderby('TP_CHAVE','ASC')->get()->pluck('TP_DESCRICAO','TP_CHAVE');
        $locais         = TabelaPadrao::Where('TP_TABELA', 'local_agenda')->orderby('TP_CHAVE','ASC')->get()->pluck('TP_DESCRICAO','TP_CHAVE');

        /*
        $clientes = DB::table('tbl_clt as c')
            ->select('c.*')
            //->where('clt_filial', Session::get('filial'))
            ->orderBy('c.clt_nome_razao','ASC')->get();
        $cliente_combo = [];
        $cliente_combo[""] = "Todos";
        foreach($clientes as $cli) {
            $cliente_combo[$cli->clt_id] = $cli->clt_nome_razao;
        }
        */

        $usuario_filial = DB::table('tbl_usuario_filial')->where('usuario', auth()->user()->login)->get()->pluck('filial')->toArray();
        $filiais = Filial::whereIn('filial_cod', $usuario_filial)
            ->orderBy('filial_descricao', 'ASC')
            ->get();
        $filiais_combo = [];
        $filiais_combo[""] = "Todas";
        foreach($filiais as $fil) {
            $filiais_combo[$fil->filial_cod] = $fil->filial_descricao;
        }

        return view('agenda-corporativa.evento.form', compact(
            'data',
            'evento',
            'consultor',
            'tipos_agenda',
            'filiais_combo',
            'locais'
        ));
    }

    public function gravar(Request $request)
    {
        $msg = '';
        $erros = false;

        $validator = Validator::make($request->all(), $this->rules($request), $this->messages());
        if ($validator->fails()) {
            $erros = $validator->errors()->all();
        } else {
            DB::beginTransaction();

            $editar = false;
            if(!empty($request->evento_id)) {
                $editar = true;
            }
            try {

                if($editar) {
                    $evento = Evento::find($request->evento_id);
                    $this->atualizarEvento($evento, $request);
                    Mail::to($evento->consultor->email)->send(new Agenda($evento, 'edicao'));
                } else {

                    if($request->todos_consultores == 'sim') {
                        $consultores = Usuario::where('rel_capacidade_agenda', 'S')->get(); // todos consultores
                    } else {
                        $consultores = Usuario::where('id', $request->consultor_id)->get(); // apenas consultores selecionado
                    }
                    foreach ($consultores as $consultor) {

                        // repetir
                        $repete = ($request->repete == 'sim') ? true : false;
                        if($repete) {
                            // configuracao da repetição
                            $repetir_inicio = Carbon::now();
                            $repetir_ate    = Carbon::createFromFormat('d/m/Y', $request->repetir_ate);
                            $dias_semana    = $request->dia_semana;

                            // flag para determinar seu fim
                            while ($repete) {

                                // verifica se dia esta entre os permitidos
                                if(in_array($this->diaSemana($repetir_inicio->dayOfWeek), $dias_semana)) {
                                    // criar o evento
                                    $evento = $this->novoEvento($consultor, $repetir_inicio->format('Y-m-d'));
                                    $this->atualizarEvento($evento, $request);
                                }

                                // verificar se chegou até o ultimo dia
                                if($repetir_inicio->format('Y-m-d') == $repetir_ate->format('Y-m-d')) {
                                    $repete = false;
                                } else {
                                    $repetir_inicio = $repetir_inicio->addDay();
                                }
                            }
                        } else {
                            $data = Carbon::createFromFormat('d/m/Y', $request->data);
                            $evento = $this->novoEvento($consultor, $data->format('Y-m-d'));
                            $this->atualizarEvento($evento, $request);
                        }

                        Mail::to($consultor->email)->send(new Agenda($evento, 'inclusao'));
                    }
                }

                DB::commit();
                $msg = 'Evento cadastrado com sucesso!';
                if($editar) {
                    $msg = 'Evento atualizado com sucesso!';
                }

            } catch (\Exception $e) {
                DB::rollback();
                $erros[] = 'Erro ao tentar salvar evento: '.$e->getMessage();
            }
        }
        $retorno = [
            'erros' => $erros,
            'msg' => $msg
        ];
        return json_encode($retorno);
    }

    private function diaSemana($dia)
    {
        $dia = (string)$dia;
        switch ($dia) {
            case "1":
                $retorno = 'segunda';
                break;
            case "2":
                $retorno = 'terca';
                break;
            case "3":
                $retorno = 'quarta';
                break;
            case "4":
                $retorno = 'quinta';
                break;
            case "5":
                $retorno = 'sexta';
                break;
            case "6":
                $retorno = 'sabado';
                break;
            case "0":
                $retorno = 'domingo';
                break;
            default:
                $retorno = $dia;
        }
        return $retorno;
    }

    private function novoEvento($consultor, $data)
    {
        $evento = new Evento();
        $evento->userid     = $consultor->id;
        $evento->login_inc  = auth()->user()->login;
        $evento->date       = $data;
        return $evento;
    }

    private function atualizarEvento($evento, $request)
    {
        $evento->project        = $request->projeto_modal ? $request->projeto_modal : null;
        $evento->type           = $request->tipo_agenda;
        $evento->starthour      = $request->horario_inicio;
        $evento->endhour        = $request->horario_fim;
        $evento->description    = $request->atividade ? $request->atividade : null;
        $evento->client         = ($request->cliente_modal) ? $request->cliente_modal : null;
        $evento->location       = $request->local;
        $evento->login_alt      = auth()->user()->login;
        $evento->save();

        return $evento;
    }

    private function rules($request)
    {
        $rules = [
            'tipo_agenda'       => 'required',
            //'cliente_modal'     => 'required',
            //'projeto_modal'     => 'required',
            'horario_inicio'    => 'required|date_format:H:i',
            'horario_fim'       => 'required|date_format:H:i',
            'local'             => 'required',
            //'atividade'         => 'required:max:255',
        ];

        if($request->projeto_modal) {
            $projeto = Projeto::find($request->projeto_modal);
            if($projeto->equip_restricao == 'S') {
                $rules['projeto_modal_restricao'] = 'required';
            }
        }

        if($request->repete == "sim") {
            $rules['repetir_ate']   = 'required|date_format:d/m/Y';
            $rules['dia_semana']    = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'tipo_agenda.required'              => 'O campo Tipo de Agenda é Obrigatório',
            'cliente_modal.required'            => 'O campo Cliente é Obrigatório',
            'projeto_modal.required'            => 'O campo Projeto é Obrigatório',
            'projeto_modal_restricao.required'  => 'Projeto está com restrição financeira',
            'horario_inicio.required'       => 'O campo Horário Início é Obrigatório',
            'horario_inicio.date_format'    => 'Horário Início inválido',
            'horario_fim.required'          => 'O campo Horário Fim é Obrigatório',
            'horario_fim.date_format'       => 'Horário Fim inválido',
            'local.required'                => 'O campo Local é Obrigatório',
            'atividade.required'            => 'O campo Atividade é Obrigatório',
            'repetir_ate.required'          => 'O campo Repetir Até é Obrigatório',
            'repetir_ate.date_format'       => 'Repetir Até inválido',
            'dia_semana.required'           => 'Você deve selecionar quais dias da semana deverá ser repetido',
        ];

        return $messages;
    }
}
