<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    protected $table = 'perfil_usuario';
    protected $primaryKey = 'PU_ID';
    public $timestamps = false;
    protected $connection = 'mysql';

    public function perfil() {
        return $this->hasOne('App\Perfil', 'PER_ID', 'PU_PER_ID');
    }

    public function usuario() {
        return $this->hasOne('App\Usuario', 'id', 'PU_USU_ID');
    }
}
