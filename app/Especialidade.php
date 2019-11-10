<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    protected $table = 'tbl_especialidade';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
