<?php

namespace App\Models\AppHrModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppHrModels\Employee;
use App\Models\AppUserModels\User;

class DailyWages extends Model
{
    public $table='daily_wages';
    protected $fillable=['amount','added_on','employee_id','user_id'];
    public $timestamps = false;

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
