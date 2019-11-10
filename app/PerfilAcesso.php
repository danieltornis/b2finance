<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilAcesso extends Model
{
    protected $table = 'perfil_acesso';
    protected $primaryKey = 'PA_ID';
    public $timestamps = false;
    protected $connection = 'mysql';

    public function perfil() {

        return $this->hasOne('App\Perfil', 'PER_ID', 'PA_PER_ID');

    }

    public function acesso() {

        return $this->hasOne('App\Acesso', 'ACE_ID', 'PA_ACE_ID');

    }
}
