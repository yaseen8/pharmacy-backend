<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppReturnModels\Config;
use Carbon\Carbon;
use App\Models\AddInventoryModels\QuantityHistory;
use App\Models\AppExpiryModels\ReturnExpiry;
use App\Models\AppExpiryModels\ReturnExpiryItem;
use App\Models\AppDisposeModels\Dispose;

class ReturnExpiryController extends Controller
{
    protected $model;
	public function __construct(ReturnExpiry $model)
	    {
		$this->model=$model;
		$this->middleware('auth');
    }
    
    public function check_expiry()
    {
        $curr = Config::first();
        $date = (integer)$curr->notify;
        $t = Carbon::today();
        $new = $t->addMonths($date);
        $expireDate =  $new->year .'-'. $new->month .'-'. $new->day ;
        $inventory = QuantityHistory::whereDate('expiry', '<=', $expireDate)->where('qty', '>', 0)->paginate(25);
        return response()->json($inventory);

    }

    public function return_expiry(Request $request)
    {
        // $this->validate($request, [
        //     'supplier' => 'required',
        //     '*.inventory' => 'required',
        //     '*.qty' => 'requried',
        //     '*.quantity_history' => 'required'
        // ]);

        $expire = $this->model::create(
            [
                'fk_supplier_id' => $request->input('supplier'),
                'fk_user_id' => $request->user()->id
            ]
            );
        if($expire){
            foreach($request['expiry_items'] as $exp){
                $item = ReturnExpiryItem::create(
                    [
                        'fk_expiry_return_id' => $expire->id,
                        'fk_inventory_id' => $exp['inventory'],
                        'fk_quantity_history_id' => $exp['quantity_history'],
                        'qty' => $exp['qty'],
                        
                    ]
                    );
                if($item){
                    $qty = QuantityHistory::where('id', $exp['quantity_history'])->first();
                    if($qty)
                    {
                        $qty['qty'] = $qty['qty'] - $qty['qty'];
                        $qty->save();
                    }
                }
            }
    
            return response()->json($expire, 200);
        }
        else
        {
            return response()->json(400);
        }
  
    }

    public function dispose_item(Request $request)
    {
        foreach($request->all() as $exp){
            $item = Dispose::create(
                [
                    'fk_inventory_id' => $exp['inventory'],
                    'fk_quantity_history_id' => $exp['quantity_history'],
                    'qty' => $exp['qty'],
                    'fk_user_id' => $request->user()->id
                    
                ]
                );
            if($item){
                $qty = QuantityHistory::where('id', $exp['quantity_history'])->first();
                if($qty)
                {
                    $qty['qty'] = $qty['qty'] - $qty['qty'];
                    $qty->save();
                }
            }
        }   
    }
}
