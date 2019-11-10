<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjetoUsuario extends Model
{
    protected $table = 'tbl_equip_user_assoc';
    protected $primaryKey = 'eua_id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
