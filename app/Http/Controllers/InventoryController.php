<?php

namespace App\Http\Controllers;

use App\Models\AddInventoryModels\Inventory;
use App\Models\AddInventoryModels\PurchasePriceHistory;
use App\Models\AddInventoryModels\QuantityHistory;
use App\Models\AddInventoryModels\SalePriceHistory;
use App\Models\AddInventoryModels\StockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    protected $model;
    public function __construct(Inventory $model)
    {
        $this->model = $model;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventory = $this->model::with('sale_price_history', 'quantity_history', 'company')->orderBy('name')->paginate(25);
        return response()->json($inventory, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function add_inventory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'pack' => 'required',
            'strength' => 'required',
            'fk_company_id' => 'required',
            'min_qty' => 'required',
            'salt' => 'required'
        ]);
        $check = Inventory::where('name', $request->input('name'))
            ->where('type', $request->input('type'))
            ->where('pack', $request->input('pack'))
            ->where('strength', $request->input('strength'))
            ->first();
        if ($check) {
            return response()->json('Exist', 400);
        } else {
            $inventory = Inventory::create(
                [
                    'name' => $request->input('name'),
                    'type' => $request->input('type'),
                    'pack' => $request->input('pack'),
                    'strength' => $request->input('strength'),
                    'min_qty' => $request->input('min_qty'),
                    'salt' => $request->input('salt'),
                    'fk_company_id' => $request->input('fk_company_id'),
                ]
            );

            return response()->json($inventory, 200);
        }
    }

    public function update_inventory(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'pack' => 'required',
            'strength' => 'required',
            'fk_company_id' => 'required',
            'min_qty' => 'required',
            'salt' => 'required'
        ]);
        $check = Inventory::where('name', $request->input('name'))
            ->where('type', $request->input('type'))
            ->where('pack', $request->input('pack'))
            ->where('strength', $request->input('strength'))
            ->where('fk_company_id', $request->input('company'))
            ->first();
        if ($check) {
            return response()->json('Exist', 400);
        } else {
            $inventory = $this->model::find($id);
            if ($inventory) {
                $inventory->update($request->all());
                return response()->json($inventory, 200);
            } else {
                return response()->json(404);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request,[
        //     '*.inventory'=>'required|exist:inventory.id',
        //     '*.sale_price' => 'required',
        //     '*.purchase_price' => 'required',
        //     '*.quantity' => 'required'
        // ]);
        $check = StockHistory::where('invoice', $request->input('invoice'))->first();
        if ($check) {
            return response()->json('exist', 400);
        } else {
            $stock = StockHistory::create(
                [
                    'invoice' => $request->input('invoice'),
                    'fk_user_id' => $request->user()->id,
                ]
            );

            foreach ($request['data'] as $inv) {

                $curr = SalePriceHistory::where('fk_inventory_id', $inv['inventory'])->whereNull('end_timestamp')->first();
                if ($curr) {
                    if (!($curr->price == $inv['sale_price']['price'])) {
                        SalePriceHistory::create(
                            [
                                'price' => $inv['sale_price']['price'],
                                'fk_inventory_id' => $inv['inventory'],
                            ]
                        );
                        $curr->end_timestamp = \Carbon\Carbon::now()->toDateTimeString();
                        $curr->save();
                    }
                } else {
                    SalePriceHistory::create(
                        [
                            'price' => $inv['sale_price']['price'],
                            'fk_inventory_id' => $inv['inventory'],
                        ]
                    );
                }

                $stock->stock_item_qty_history()->create(
                    [
                        'qty' => $inv['quantity']['qty'],
                        'fk_supplier_id' => $inv['supplier'],
                        'fk_inventory_id' => $inv['inventory'],
                    ]
                );
                $price = $inv['purchase_price']['price'];
                $check_purchase = QuantityHistory::with('purchase_price_history')
                    ->where('fk_inventory_id', $inv['inventory'])
                    ->where('expiry', $inv['quantity']['expiry'])
                    ->whereHas('purchase_price_history', function ($q) use ($price) {
                        $q->where('price', [$price]);
                    })
                    ->first();
                if ($check_purchase) {
                    $check_purchase->qty = $check_purchase->qty + $inv['quantity']['qty'];
                    $check_purchase->save();
                } else {
                    $purchase = PurchasePriceHistory::create(
                        [
                            'price' => $inv['purchase_price']['price'],
                            'date' => Carbon::today(),
                            'fk_inventory_id' => $inv['inventory'],
                        ]
                    );
                    $purchase->quantity_history()->create(
                        [
                            'qty' => $inv['quantity']['qty'],
                            'expiry' => $inv['quantity']['expiry'],
                            'fk_inventory_id' => $inv['inventory'],
                        ]
                    );
                }

            }
            return response()->json('Success', 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->model::with('quantity_history', 'supplier', 'company')->where('id', $id)->first();
        if ($data) {
            return response()->json($data, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_price(Request $request)
    {
        $obj = SalePriceHistory::where('fk_inventory_id', $request->inventory)->whereNull('end_timestamp')->first();
        if ($obj) {
            $sale_price = SalePriceHistory::create(
                [
                    'price' => $request->price,
                    'fk_inventory_id' => $request->inventory,
                ]
            );
            $obj->end_timestamp = \Carbon\Carbon::now('+5 hours');
            $obj->save();
            return response()->json($sale_price, 200);
        }
        return response()->json(404);
    }

    public function search(Request $request)
    {
        $name = $request->input('q');
        if (strlen($name) > 1) {
            $inventory = $this->model::where('name', 'like', $name . '%');
            return response()->json($inventory->orderBy('name', 'desc')->get());
        }
        return response()->json(false, 404);

    }

    public function select_list()
    {
        $inventory = $this->model->get();
        return response()->json($inventory, 200);
    }

    public function search_name(Request $request)
    {
        $name = $request->input('name');
        $inv = $this->model::with('sale_price_history', 'quantity_history', 'company')->where('name', 'like', $name . '%')->orWhere('salt', 'like', $name . '%')->paginate(50);
        return response()->json($inv, 200);
    }
}
