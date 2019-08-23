<?php

namespace App\Models\AppSaleModels;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $table='sale';
    protected $fillable=['total','discount','grand_total', 'payment','fk_user_id', 'timestamp'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User'::class, 'fk_user_id');
    }

    public function sale_items()
    {
        return $this->hasMany('App\Models\AppSaleModels\SaleItems'::class, 'fk_sale_id', 'id');
    }

    public function return_item()
    {
        return $this->hasMany('App\Models\AppReturnModels\ReturnItem'::class, 'fk_sale_id', 'id');
    }

    // public function setTotalAttribute($value)
    // {
    //     $this->attributes['total'] = (int)($value);
    // }
}
