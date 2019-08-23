<?php

namespace App\Models\AppSupplierModels;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $table='supplier';
    protected $fillable=['name','mobile','email','landline', 'address'];
    public $timestamps = false;

    public function inventory(){
        return $this->hasMany(Inventory::class);
    }

    public function supplier_payment(){
        return $this->hasMany('App\Models\AppSupplierModels\SupplierPayment', 'fk_supplier_id', 'id');
    }
}
