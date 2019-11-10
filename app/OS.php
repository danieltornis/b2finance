<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OS extends Model
{
    protected $table = 'tbl_os';
    protected $primaryKey = 'os_id';
    public $timestamps = false;
    protected $connection = 'mysql';
}
