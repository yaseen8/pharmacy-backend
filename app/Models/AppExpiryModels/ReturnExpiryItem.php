<?php

namespace App\Models\AppExpiryModels;

use Illuminate\Database\Eloquent\Model;

class ReturnExpiryItem extends Model
{
    public $table = 'expiry_items';
    protected $fillable = ['qty', 'fk_expiry_return_id', 'fk_inventory_id', 'fk_quantity_history_id'];
    public $timestamps = false;
    public $appends = ['inventory'];

    public function inventory()
    {
        return $this->belongsTo('App\Models\AddInventoryModels\Inventory'::class, 'fk_inventory_id');
    }

    public function expiry_return()
    {
        return $this->belongsTo(ReturnExpiry::class, 'fk_expiry_return_id');
    }

    public function getInventoryAttribute()
    {
        return $this->inventory()->first();
    }
}
