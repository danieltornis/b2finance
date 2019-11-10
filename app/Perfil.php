<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';
    protected $primaryKey = 'PER_ID';
    public $timestamps = false;
    protected $connection = 'mysql';

    public function perfilAcessos() {

        return $this->hasMany('App\PerfilAcesso', 'PA_PER_ID', 'PER_ID');

    }
}
