<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'PROD_ID';
    public $timestamps = false;
    protected $connection = 'mysql';
}