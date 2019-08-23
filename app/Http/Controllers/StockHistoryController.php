<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddInventoryModels\StockHistory;
use App\Models\AddInventoryModels\StockItemQtyHistory;

class StockHistoryController extends Controller
{
    protected $model;
	public function __construct(StockHistory $model)
	    {
		$this->model=$model;
		$this->middleware('auth');
    }

    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $stock=$this->model::with('user')->whereBetween('date',[$from, $to])->orderBy('date', 'DESC')->get();
        return response()->json($stock, 200);
    }

    public function get_stock_quantity(Request $request)
    {
        // dd($request->input('stock_history'));
        $quantity = StockItemQtyHistory::with('inventory','supplier')->where('fk_stock_history_id' , $request->input('stock_history'))->get();
        return response()->json($quantity, 200);
    }
}
