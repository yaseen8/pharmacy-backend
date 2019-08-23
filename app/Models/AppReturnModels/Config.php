<?php

namespace App\Models\AppReturnModels;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'config';
    protected $fillable = ['notify', 'check_qty'];
    public $timestamps = false;
}
