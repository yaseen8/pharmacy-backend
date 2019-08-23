<?php

namespace App\Models\AppReturnModels;

use Illuminate\Database\Eloquent\Model;

class ReturnQuantity extends Model
{
    public $table='returned_qty';
    protected $fillable=['qty', 'fk_returned_item_id', 'fk_purchase_price_id'];
    public $timestamps = false;

    public function return_item(){
        return $this->belongsTo(ReturnItem::class, 'fk_returned_item_id');
    }
}
