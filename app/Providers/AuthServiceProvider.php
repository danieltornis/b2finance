<?php

namespace App\Providers;

use App\Acesso;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $acessos = Acesso::all();

        foreach($acessos as $acesso) {
            $gate->define($acesso->ACE_PERMISSAO, function($user) use($acesso) {

                foreach($user->perfilUsuarios as $perfil_usuario){
                    $perfil = $perfil_usuario->perfil;

                    foreach($perfil->perfilAcessos as $perfil_acesso) {
                        $a = $perfil_acesso->acesso;

                        if($acesso->ACE_ID == $a->ACE_ID && $a->ACE_ATIVO == 'S') {
                            return true;
                        };
                    }
                }
                return false;
            });
        }
    }
}
