<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class SalePriceHistory extends Model
{
    public $table='sale_price_history';
    protected $fillable=['price','fk_inventory_id'];
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'fk_inventory_id');
    }

    public function sale_items()
    {
        return $this->belongsTo('App\Models\AppSaleModels\SaleItems'::class, 'fk_sale_price_id', 'id');
    }
}
