<?php

namespace App\Models\AppSupplierModels;

use Illuminate\Database\Eloquent\Model;

class SupplierPaymentImage extends Model
{
	public $table='payment_cheque_receipt_imgs';
	protected $fillable=['img','fk_payment_id'];
	public $timestamps = false;
}
