<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'tbl_clt';
    protected $primaryKey = 'clt_id';
    public $timestamps = false;
    protected $connection = 'mysql';

    public function filial()
    {
        return $this->hasOne('App\Filial', 'filial_cod', 'clt_filial');
    }
}
