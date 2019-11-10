<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Usuario extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql';

    public function getRememberTokenName() {
        return '';
    }

    public function setRememberToken($value)
    {
        return '';
    }

    public function perfilUsuarios() {

        return $this->hasMany('App\PerfilUsuario', 'PU_USU_ID', 'id');

    }

    public function filial()
    {
        return $this->hasOne('App\Filial', 'filial_cod', 'filialmestre');
    }

    public function especialidade()
    {
        return $this->hasOne('App\Especialidade', 'id', 'especialidade_colaborador');
    }
}
