<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql';

    protected $dates = [
        'date'
    ];

    public function consultor()
    {
        return $this->hasOne('App\Usuario', 'id', 'userid');
    }

    public function cliente()
    {
        return $this->hasOne('App\Cliente', 'clt_id', 'client');
    }

    public function projeto()
    {
        return $this->hasOne('App\TblEquip', 'equip_id', 'project');
    }

    public function tipo()
    {
        return $this->hasOne('App\TabelaPadrao', 'TP_CHAVE', 'type')->where('TP_TABELA', 'tipo_agenda');
    }


    public function scopeTypeDescricao()
    {

        $type = $this->type;
        $client = '-';

        if($type == 4) {
            $client = "PARTICULAR";
        } else if($type == 5) {
            $client = "REUNIÃO";
        } else if($type == 6) {
            $client = "DESCANSO";
        } else if($type == 7) {
            $client = "COMERCIAL";
        } else if($type == 8) {
            $client = "FERIADO";
        } else if($type == 9) {
            $client = "FÉRIAS";
        } else if($type == 10) {
            $client = "TREINAMENTO";
        } else if($type == 3) {
            if($this->cliente) {
                $client = $this->cliente->clt_nome_razao;
            }

        } else if($type == 2) {
            if($this->cliente) {
                $client = $this->cliente->clt_nome_razao;
            }
        } else {
            if($this->cliente) {
                $client = $this->cliente->clt_nome_razao;
            }
        }

        return $client;
    }

    public function scopeLocationDescricao()
    {

        $descricao = "-";
        switch ($this->location) {
            case "1":
                $descricao = "Home Office";
                break;
            case "2":
                $descricao = "Cliente";
                break;
            default:
                $descricao = "B2FINANCE";
                break;
        }
        return $descricao;
    }

}
