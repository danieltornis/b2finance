<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OSAtividade extends Model
{
    protected $table = 'tbl_os_atv';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
