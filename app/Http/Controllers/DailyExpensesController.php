<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppHrModels\DailyExpenses;

class DailyExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $expenses = DailyExpenses::with('user')->orderBy('added_on', 'desc')->paginate(25);
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'amount'=>'required'
        ]);

        $expense = DailyExpenses::create(
            [
                'amount' => $request->amount,
                'name' => $request->name,
                'user_id' => $request->user()->id
            ]
            );
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'amount'=>'required'
        ]);

        $expense = DailyExpenses::findOrFail($id);
        $expense->update(
            [
                'amount' => $request->amount,
                'name' => $request->name,
                'user_id' => $request->user()->id
            ]
            );
        return response()->json($expense);
    }
}
