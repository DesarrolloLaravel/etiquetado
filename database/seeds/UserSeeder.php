<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('users')->insert(array(
            'users_user'		=> 'admin',
            'users_name'		=> 'Javier Villarroel',
            'users_email'		=> 'administrador@gmail.com',
            'password'	=> \Hash::make('123123'),
            'users_role'        => 'administracion'
        ));

        \DB::table('users')->insert(array(
            'users_user'        => 'gerencia',
            'users_name'        => 'Javier Villarroel',
            'users_email'       => 'recepcion@gmail.com',
            'password'    => \Hash::make('123123'),
            'users_role'        => 'gerencia'
        ));

        \DB::table('users')->insert(array(
            'users_user'        => 'recepcion',
            'users_name'        => 'Javier Villarroel',
            'users_email'       => 'recepcion@gmail.com',
            'password'    => \Hash::make('123123'),
            'users_role'        => 'recepcion'
        ));

        \DB::table('users')->insert(array(
            'users_user'        => 'produccion',
            'users_name'        => 'Javier Villarroel',
            'users_email'       => 'produccion@gmail.com',
            'password'    => \Hash::make('123123'),
            'users_role'        => 'produccion'
        ));

        \DB::table('users')->insert(array(
            'users_user'        => 'empaque',
            'users_name'        => 'Javier Villarroel',
            'users_email'       => 'empaque@gmail.com',
            'password'    => \Hash::make('123123'),
            'users_role'        => 'empaque'
        ));

        \DB::table('users')->insert(array(
            'users_user'        => 'almacenamiento',
            'users_name'        => 'Javier Villarroel',
            'users_email'       => 'almacenamiento@gmail.com',
            'password'    => \Hash::make('123123'),
            'users_role'        => 'almacenamiento'
        ));
    }
}
