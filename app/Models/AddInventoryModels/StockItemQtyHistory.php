<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class StockItemQtyHistory extends Model
{
    public $table='stock_item_qty_history';
    protected $fillable=['qty', 'fk_stock_history_id', 'fk_inventory_id', 'fk_supplier_id'];
    public $timestamps = false;

    public function stock_history()
    {
        return $this->belongsTo(StockHistory::class, 'fk_stock_history_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'fk_inventory_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\AppSupplierModels\Supplier'::class, 'fk_supplier_id');
    }

}
