<?php

namespace App\Models\AppSaleModels;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    public $table='sale_items';
    protected $fillable=['total','fk_inventory_id','fk_sale_price_id', 'fk_sale_id'];
    public $timestamps = false;
    public $appends = ['purchased_quantity', 'quantity_history', 'sale_price'];

    public function sale()
    {
        return $this->belongsTo('App\Models\AppSaleModels\Sale'::class, 'fk_sale_id');
    }

    public function sale_price()
    {
        return $this->belongsTo('App\Models\AddInventoryModels\SalePriceHistory'::class, 'fk_sale_price_id');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Models\AddInventoryModels\Inventory'::class, 'fk_inventory_id');
    }

    public function purchased_quantity()
    {
        return $this->hasMany('App\Models\AppSaleModels\PurchasedQuantity'::class, 'fk_sale_item_id', 'id');
    }

    public function getInventoryDetailAttribute()
    {
        return $this->inventory()->get();
    }

    public function getPurchasedQuantityAttribute()
    {
        return $this->purchased_quantity()->get();
    }

    public function getQuantityHistoryAttribute()
    {
        return $this->inventory->quantity_history()->get();
    }

    public function getSalePriceAttribute()
    {
        return $this->sale_price()->first();
    }
}
