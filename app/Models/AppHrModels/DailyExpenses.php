<?php

namespace App\Models\AppHrModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppUserModels\User;

class DailyExpenses extends Model
{
    public $table='daily_expenses';
    protected $fillable=['name','amount','added_on','user_id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
