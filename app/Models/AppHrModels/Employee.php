<?php

namespace App\Models\AppHrModels;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $table='employees';
    protected $fillable=['name'];
    public $timestamps = false;
}
