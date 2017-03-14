<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;
    
    protected $table = 'users';
    protected $primaryKey = 'users_id';
    protected $fillable = ['users_user',
                            'password',
                            'users_name',
                            'users_email',
                            'users_role'];

    protected $dates = ['deleted_at'];

    /*public function getAuthPassword()
    {
        return $this->users_password;
    }*/

    public function lotes()
    {
        return $this->hasMany(  'App\Models\Lote',
                                'lote_users_id',
                                'users_id');
    }

    public function isRecepcion(){
        return $this->users_role == "recepcion";
    }

    public function isAlmacenamiento(){
        return $this->users_role == "almacenamiento";
    }

    public function isEmpaque(){
        return $this->users_role == "empaque";
    }

    public function isProduccion(){
        return $this->users_role == "produccion";
    }

}