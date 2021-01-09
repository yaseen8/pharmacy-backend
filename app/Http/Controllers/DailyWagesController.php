<?php

namespace App\Http\Controllers;
use App\Models\AppHrModels\DailyWages;


use Illuminate\Http\Request;

class DailyWagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wages = DailyWages::with('employee','user')->orderBy('added_on', 'desc')->paginate(25);
        return response()->json($wages);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'amount'=>'required',
            'employee_id'=>'required',
        ]);

        $wages = DailyWages::create(
            [
                'amount' => $request->amount,
                'employee_id' => $request->employee_id,
                'user_id' => $request->user()->id
            ]
            );
        return response()->json($wages);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'amount'=>'required',
            'employee_id'=>'required',
        ]);

        $wages = DailyWages::findOrFail($id);
        $wages->update(
            [
                'amount' => $request->amount,
                'employee_id' => $request->employee_id,
                'user_id' => $request->user()->id
            ]
            );
        return response()->json($wages);
    }
}
