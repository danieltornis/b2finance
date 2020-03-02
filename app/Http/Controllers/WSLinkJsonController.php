<?php

namespace App\Http\Controllers;

use App\Produto;
use App\TabelaPadrao;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WSLinkJsonController extends Controller
{
    public function listar(Request $request) {

        $exportar = Produto::select('LIN_LINK as link','LIN_DT_CORTE as data_corte')
            ->where('LIN_PRODUTO',$request->produto)
            ->where('LIN_CODIGO',$request->codigo)
            ->where('LIN_VERSAO',$request->versao)->first();

        $produto = TabelaPadrao::where('TP_TABELA','produtos')->where('TP_CHAVE',$request->produto)->first()->TP_DESCRICAO;

        if($exportar==null) {
            $retorno = array(
                'status' => 'ERRO',
                'mensagem' => 'O link '. $produto .' nao esta liberado para o CNPJ '. $request->cnpj .' Entre em contato com o representante da Logithink.'
            );
        } else {
            if ($exportar->status == 'A') {
                if ($exportar->data_corte != null) {
                    if ($exportar->data_corte > date("Y-m-d")) {

                        $datetime1 = Carbon::createFromFormat('Y-m-d',$exportar->data_corte);
                        $datetime2 = Carbon::createFromFormat('Y-m-d',date("Y-m-d"));

                        $interval = $datetime1->diff($datetime2);
                        $dias_corte = $interval->format('%a');

                        if ($dias_corte <= "10") {
                            $msg = 'Periodo de uso do ' . $produto . ' ira expirar em ' . $dias_corte . ' dia(s). Entre em contato com o representante da b2finance para renovar o seu periodo de uso.';
                        } else {
                            $msg = '';
                        }

                        $retorno = array(
                            'status' => 'OK',
                            'mensagem' => $msg
                        );
                    } else {
                        $retorno = array(
                            'status' => 'INATIVO',
                            'mensagem' => 'Periodo de uso do '.$produto.' expirou! Entre em contato com o representante da b2finance para renovar o seu periodo de uso.'
                        );
                    }
                } else {
                    $retorno = array(
                        'status' => 'OK',
                        'mensagem' => ''
                    );
                }
            } else {
                $retorno = array(
                    'status' => 'INATIVO',
                    'mensagem' => $produto . ' esta bloqueado! Entre em contato com o representante da b2finance para renovar o seu periodo de uso.'
                );
            }
        }

        return response()->json($retorno);
    }
}
