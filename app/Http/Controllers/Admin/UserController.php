<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;

use App\Models\User;
use App\Models\Lote;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $users = User::all();

        //si es petición ajax
        if($request -> ajax()){

            //si no hay cliente
            if($users-> count() == 0){
                return '{"data":[]}';
            }

            //si no hay cliente
            $dt_json = '{ "data": [';

            //guarda en json los datos de todos los clientes
            foreach ($users as $user) {
                $dt_json .= '["'.$user->users_id.'","'
                                .$user->users_name.'","'
                                .$user->users_email.'","'
                                .$user->users_role.'"],';
            }

            //elimina la ultima coma del json
            $dt_json = substr($dt_json, 0, -1);

            //cierra el json
            $dt_json .= "] }";

            return $dt_json;
        }
        else{
            return view('admin.user.index', compact('users'));    
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        //
        if($request -> ajax()){
            //crea un cliente con los datos del request

            // $v = \Validator::make($request->all(), [
            //     'users_name' => 'max:255',
            //     'users_email' => 'unique:users,users_email',
            //     'users_user' => 'unique:users,users_user',
            //     'password' => 'min:5'
            // ]); 
           \DB::table('users')->insert(array(
            'users_user'		=> $request->users_user,
            'users_name'		=> $request->users_name,
            'users_email'		=> $request->users_email,
            'password'	=> \Hash::make($request->users_password),
            'users_role'        => $request->users_role
        	));
            
                //respondo con un json que contiene los errores
            return response()->json([
                "ok"

            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        if($request->ajax()){

            $lote = Lote::where('lote_users_id',$request->users_id)->count();

            
            if($lote == 0 ){
                //se busca el cliente a modificar
                $user = User::findOrFail($request->users_id);

                return response()->json($user);        
            }
            else{
                return response()->json(["nok"]);
            }
    
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        //
        if($request-> ajax()){

            //busca por el id al elaborador
            $user = User::findOrFail($request->users_id);

            //genera un array con la info del update

           
            $info = array(
            	'users_user' => $request->users_user,
                'users_name' => $request->users_name,
                'users_email' => $request->users_email,
                'users_role' => $request->users_role,
                'password' => \Hash::make($request->users_password)
                );

            //añade la info al elaborador encontrado
            $user->fill($info);

            //guarda en la bd el elaborador ya modificado
            $user->save();

            //retorna la respuesta
            return response()->json([
                "ok"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
        if($request->ajax())
        {
            //se crea el validador con la información enviada desde el cliente
            //y con las reglas de validación respectiva
            $v = \Validator::make($request->all(), [
                'cliente_id' => 'required|exists:cliente,cliente_id'
            ]);
            //si falla la validación
            if ($v->fails())
            {
                //respondo con un json que contiene los errores
                return response()->json($v->errors());
            }
            //busco la elaboradora a eliminar
            $cliente = Cliente::findOrFail($request->cliente_id);
            //elimino la compañia
            $cliente->delete();

            //respuesta al cliente
            return response()->json([
                "ok"
            ]);
        }
    }
}
