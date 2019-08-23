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
        $qty = $this->model->first()->check_qty;
        $inventory = Inventory::with('quantity_history')->get();
        foreach($inventory as $i){
            $check = 0;
            foreach($i['quantity_history'] as $q){
                $check = $check + (int)($q['qty']);
            }
            if($check < $qty){
                $data[] = $i;
            }
        }
        return response()->json($data, 200);
    }
}
