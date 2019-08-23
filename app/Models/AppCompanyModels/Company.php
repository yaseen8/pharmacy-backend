<?php

namespace App\Models\AppCompanyModels;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $table='company';
    protected $fillable=['name'];
    public $timestamps = false;

    public function inventory(){
        return $this->hasMany(Inventory::class);
    }
}
