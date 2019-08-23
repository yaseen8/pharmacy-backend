<?php

namespace App\Models\AppDisposeModels;

use Illuminate\Database\Eloquent\Model;

class Dispose extends Model
{
    public $table='dispose_items';
    protected $fillable=['qty', 'fk_inventory_id', 'fk_quantity_history_id','fk_user_id'];
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo('App\Models\AddInventoryModels\Inventory'::class, 'fk_inventory_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User'::class, 'fk_user_id');
    }
}
