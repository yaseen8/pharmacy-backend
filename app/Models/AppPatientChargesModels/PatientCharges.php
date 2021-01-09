<?php

namespace App\Models\AppPatientChargesModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppSaleModels\Sale;

class PatientCharges extends Model
{
    public $table='patient_charges';
    protected $fillable=['payment', 'timestamp','sale_id'];
    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
