<?php

namespace App\Models\AppExpiryModels;

use Illuminate\Database\Eloquent\Model;

class ReturnExpiry extends Model
{
    public $table='expiry_return';
    protected $fillable=['fk_supplier_id', 'fk_user_id'];
    public $timestamps = false;

    public function expiry_items()
    {
        return $this->hasMany(ReturnExpiryItem::class,'fk_expiry_return_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\AppUserModels\User'::class,'fk_user_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\AppSupplierModels\Supplier'::class,'fk_supplier_id', 'id');
    }
}

   
