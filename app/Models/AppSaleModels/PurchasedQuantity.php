<?php

namespace App\Models\AppSaleModels;

use Illuminate\Database\Eloquent\Model;

class PurchasedQuantity extends Model
{
    public $table='purchased_qty';
    protected $fillable=['qty','fk_purchase_price_id','fk_sale_item_id'];
    public $timestamps = false;
    public $appends = ['purchase_price'];


    public function sale_items()
    {
        return $this->belongsTo('App\Models\AppSaleModels\SaleItems', 'fk_sale_item_id');
    }

    public function purchase_price()
    {
        return $this->belongsTo('App\Models\AddInventoryModels\PurchasePriceHistory', 'fk_purchase_price_id');
    }

    public function getPurchasePriceAttribute()
    {
        return $this->purchase_price()->get();
    }

     public function getExpiryAttribute(){
        return $this->purchase_price()->inventory()->get();
    }


}
