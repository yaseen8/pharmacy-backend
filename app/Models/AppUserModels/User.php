<?php

namespace App\Models\AppUserModels;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public $table='users';
    protected $keyType='string';
    public $timestamps = false;

    protected $fillable = [
        'username','password'
    ];

    protected $hidden = [
        'password',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function stock_history()
    {
        return $this->hasMany('App/Models/AddInventoryModels/StockHistory', 'fk_user_id', 'id');
    }

    public function sale()
    {
        return $this->hasMany('App/Models/AppSaleModels/Sale', 'fk_user_id', 'id');
    }

    public function return_item()
    {
        return $this->hasMany('App/Models/AppReturnModels/ReturnItem', 'fk_user_id', 'id');
    }
}
