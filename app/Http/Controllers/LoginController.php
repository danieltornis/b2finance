<?php

namespace App\Http\Controllers;

use App\Usuario;
use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){

        if(Auth::check()) {
            return Redirect::route('index');
        }

        return view('login');
    }

    public function autenticar(){
        $usuario = Usuario::where('login', Input::get('usuario'))->first();

        if($usuario) {
            if(base64_encode(Input::get('senha')) == $usuario->password) {
                Auth::loginUsingId($usuario->id);

                return Redirect::route('index');
            }
        }

        return Redirect::route('login')->withErrors(['Usuário/Senha inválidos!'])
            ->withInput();
    }

    public function autenticar2(){
        $usuario = Usuario::where('login', Input::get('usuario'))->first();

        if($usuario) {
            Auth::loginUsingId($usuario->id);
            return Redirect::route('index');
        }

        return Redirect::route('login')->withErrors(['Usuário/Senha inválidos!'])
            ->withInput();
    }

    public function logout() {

        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }
}
