<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class QuantityHistory extends Model
{
    public $table='quantity_history';
    protected $fillable=['qty','expiry', 'fk_purchase_price_id', 'fk_inventory_id'];
    public $timestamps = false;
    public $appends = ['purchase_price', 'inventory_detail', 'company'];

    public function purchase_price_history()
    {
        return $this->belongsTo(PurchasePriceHistory::class, 'fk_purchase_price_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'fk_inventory_id');
    }

    public function getPurchasePriceAttribute()
    {
        return $this->purchase_price_history->price;
    }

    public function getInventoryDetailAttribute()
    {
        return $this->inventory()->first();
    }

   public function getCompanyAttribute()
    {
        return $this->inventory()->first()->company;
    }
}
