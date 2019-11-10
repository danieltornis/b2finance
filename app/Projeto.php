<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    protected $table = 'tbl_equip';
    protected $primaryKey = 'equip_id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
