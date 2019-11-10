<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcecaoDespesa extends Model
{
    protected $table = 'tbl_excecao';
    protected $primaryKey = 'exc_id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
