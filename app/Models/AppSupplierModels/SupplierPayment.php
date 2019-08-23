<?php

namespace App\Models\AppSupplierModels;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    public $table='supplier_payment';
    protected $fillable=['total_amount','amount','balance','payment_via','fk_supplier_id', 'fk_user_id'];
    public $timestamps = false;

    public function supplier(){
        return $this->belongsTo('App\Models\AppSupplierModels\Supplier','fk_supplier_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User','fk_user_id');
    }

    public function supplier_payment_image(){
        return $this->hasMany('App\Models\AppSupplierModels\SupplierPaymentImage', 'fk_payment_id', 'id');
    }
}
