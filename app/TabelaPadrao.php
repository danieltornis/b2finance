<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TabelaPadrao extends Model
{
    protected $table = 'tabela_padrao';
    protected $primaryKey = 'TP_ID';
    public $timestamps = false;
}
