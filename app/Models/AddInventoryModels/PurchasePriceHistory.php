<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class PurchasePriceHistory extends Model
{
    public $table='purchase_price_history';
    protected $fillable=['price','date','fk_inventory_id'];
    public $timestamps = false;
    // public $appends = ['qty_history'];

    public function inventory(){
        return $this->belongsTo(Inventory::class,'fk_inventory_id');
    }

    public function quantity_history(){
        return $this->hasMany('App\Models\AddInventoryModels\QuantityHistory'::class,'fk_purchase_price_id', 'id');
    }

    public function purchased_quantity(){
        return $this->hasMany('App\Models\AppSaleModels\PurchasedQuantity'::class,'fk_purchase_price_id', 'id');
    }

    public function getQtyHistoryAttribute()
    {
        return $this->inventory->quantity_history()->get();
    }
}
