<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    public $table='stock_history';
    protected $fillable=['date','invoice','fk_user_id'];
    public $timestamps = false;
    public $appends = ['user'];

    public function stock_item_qty_history()
    {
        return $this->hasMany(StockItemQtyHistory::class, 'fk_stock_history_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User'::class, 'fk_user_id');
    }

    public function getUserAttribute()
    {
        return $this->user()->first()->name;
    }
}
