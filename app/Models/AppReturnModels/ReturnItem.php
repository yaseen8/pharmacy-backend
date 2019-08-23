<?php

namespace App\Models\AppReturnModels;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    public $table='returned_items';
    protected $fillable=['total','qty','fk_inventory_id', 'fk_user_id', 'fk_sale_id'];
    public $timestamps = false;

    public function inventory(){
        return $this->belongsTo('App\Models\AddInventoryModels\Inventory'::class, 'fk_inventory_id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\AppSaleModels\Sale'::class, 'fk_sale_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User'::class, 'fk_user_id');
    }
}
