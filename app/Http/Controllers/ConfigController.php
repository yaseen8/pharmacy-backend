<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppReturnModels\Config;
use App\Models\AddInventoryModels\Inventory;

class ConfigController extends Controller
{
    protected $model;
    public function __construct(Config $model)
    {
        $this->model=$model;
        $this->middleware('auth');
    }

    public function check_product_qty()
    {
        $data = array();
        $inventory = Inventory::with('quantity_history','company')->get();
        foreach($inventory as $i){
            $check = 0;
            $min_qty = $i->min_qty;
            foreach($i['quantity_history'] as $q){
                $check = $check + (int)($q['qty']);
            }
            if($check < $min_qty){
                $data[] = $i;
            }
        }
        return response()->json($data, 200);
    }

    public function check_minimum_by_company(Request $reqest)
    {
        $data = array();
        $inventory = Inventory::with('quantity_history','company')
                        ->whereHas('company', function ($query) {
                            $query->where('name', 'like', \Request::input('name') . '%');
                        })
                        ->get();
        foreach($inventory as $i){
            $check = 0;
            $min_qty = $i->min_qty;
            foreach($i['quantity_history'] as $q){
                $check = $check + (int)($q['qty']);
            }
            if($check < $min_qty){
                $data[] = $i;
            }
        }
        return response()->json($data, 200);
    }
}
