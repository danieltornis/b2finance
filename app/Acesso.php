<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    protected $table = 'acesso';
    protected $primaryKey = 'ACE_ID';
    public $timestamps = false;
    protected $connection = 'mysql';
}
