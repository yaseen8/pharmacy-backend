<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppPatientChargesModels\PatientCharges;

class PatientChargesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create_charges(Request $request)
    {
        $charge = PatientCharges::create(
            [
                'name' => $request->name,
                'payment' => $request->payment,
                'fk_user_id' => $request->user()->id
            ]
            );
        return response()->json($charge, 201);
    }
}
