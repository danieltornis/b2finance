<?php

namespace App\Http\Controllers\AgendaCorporativa;

use App\Evento;
use App\Mail\Agenda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class ExcluirController extends Controller
{
    public function index($id)
    {
        $evento = Evento::find($id);

        if(Gate::allows('agenda_excluir')) {
            $login_inc = $evento->login_inc;
            if(Gate::allows('agenda_excluir_todas')) {
                if(auth()->user()->login == $login_inc) {
                    $tem_permissao = true;
                } else {
                    if(Gate::allows('agenda_excluir')) {
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
            $retorno = [
                'erros' => ['Acesso Negado']
            ];
            return json_encode($retorno);
        }


        try {

            Mail::to($evento->consultor->email)->send(new Agenda($evento, 'exclusao'));
            $evento->delete();
            $retorno = [
                'msg'   => 'Evento excluído com sucesso!'
            ];
        } catch (\Exception $e) {
            $erros[] = 'Erro ao tentar excluir evento: '.$e->getMessage();
            $retorno = [
                'erros' => $erros,
                'msg'   => ''
            ];
        }
        return json_encode($retorno);
    }

}
