<?php

namespace App\Models\AddInventoryModels;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    public $table='inventory';
    protected $fillable=['name','type','pack','strength', 'fk_company_id'];
    public $timestamps = false;
    public $appends=['sale_price', 'total_quantity', 'sale_id'];

    public function supplier()
    {
        return $this->belongsTo('App\Models\AppSupplierModels\Supplier'::class,'fk_supplier_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\AppCompanyModels\Company'::class,'fk_company_id');
    }

    public function getSalePriceAttribute()
    {
        $sale_price= $this->sale_price_history()->whereNull('end_timestamp')->first();
        if($sale_price)
            return $sale_price->price;
        return null;
    }

    public function getSaleIdAttribute()
    {
        $sale =  $this->sale_price_history()->whereNull('end_timestamp')->first();
        if($sale)
            return $sale->id;
        return null;
        
    }


    public function getTotalQuantityAttribute()
    {
        return $this->quantity_history()->sum('qty');
    }

    public function sale_price_history()
    {
        return $this->hasMany(SalePriceHistory::class, 'fk_inventory_id', 'id');
    }

    public function purchase_price_history()
    {
        return $this->hasMany(PurchasePriceHistory::class, 'fk_inventory_id', 'id');
    }

    public function quantity_history()
    {
        return $this->hasMany(QuantityHistory::class, 'fk_inventory_id', 'id');
    }

    public function sale_items()
    {
        return $this->hasMany('App\Models\AppSaleModels\SaleItems'::class, 'fk_inventory_id', 'id');
    }

    public function return_items()
    {
        return $this->hasMany('App\Models\AppReturnModels\ReturnItem'::class, 'fk_inventory_id', 'id');
    }

    public function getQtyHistoryAttribute()
    {
        return $this->quantity_history()->get();
    }

}
