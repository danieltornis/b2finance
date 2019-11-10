<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'tbl_filial';
    protected $primaryKey = 'filial_cod';
    public $timestamps = false;
    protected $connection = 'mysql';
}
