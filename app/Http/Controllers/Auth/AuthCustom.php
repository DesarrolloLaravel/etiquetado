<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\MessageBag;

class AuthCustom extends Controller
{
    public function postLogin(Request $request)
    {
        //validamos
        $v2 = \Validator::make($request->all(),[
                'usuario' => 'required', 
                'contrasena' => 'required',
                ]);

            if($v2->fails())
            {
                return redirect()->back()->withErrors($v2)
                        ->withInput();
            }
        
        if (\Auth::attempt(['users_user' => $request->usuario, 'password' => $request->contrasena]))
        {
            if(\Auth::user()->users_role == "administracion")
            {
                return redirect()->intended('admin/home');
            }
            elseif (\Auth::user()->users_role == "gerencia") {
                # code...
                return redirect()->intended('geren/home');
            }
            elseif (\Auth::user()->users_role == "recepcion") {
                # code...
                return redirect()->intended('recepcion/home');
            }
            elseif (\Auth::user()->users_role == "produccion") {
                # code...
                return redirect()->intended('produccion/home');
            }
            elseif (\Auth::user()->users_role == "empaque") {
                # code...
                return redirect()->intended('empaque/home');
            } 
            elseif (\Auth::user()->users_role == "almacenamiento") {
                # code...
                return redirect()->intended('almacenamiento/home');
            }
        }
        else
        {
            $errors = new MessageBag(['password' => ['El usuario o contraseña son incorrectos.']]);

            return redirect()->back()->withErrors($errors)
                        ->withInput(\Input::except('password'));
        }

    }
 
    public function getLogout()
    {
        // Cerramos la sesión
        \Auth::logout();

        // Volvemos al login y mostramos un mensaje indicando que se cerró la sesión
        return redirect('/')->with('logout', 'La sesión fue cerrada exitosamente');
    }
}
