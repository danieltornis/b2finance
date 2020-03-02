<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'link';
    protected $primaryKey = 'LIN_ID';
    public $timestamps = false;
    protected $connection = 'mysql';
}
