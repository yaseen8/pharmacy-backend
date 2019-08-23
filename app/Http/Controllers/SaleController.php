<?php

namespace App\Http\Controllers;

use App\Models\AddInventoryModels\QuantityHistory;
use App\Models\AppReturnModels\ReturnItem;
use App\Models\AppReturnModels\ReturnQuantity;
use App\Models\AppSaleModels\PurchasedQuantity;
use App\Models\AppSaleModels\Sale;
use App\Models\AppSaleModels\SaleItems;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $model;
    public function __construct(Sale $model)
    {
        $this->model = $model;
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        // $this->validate($request,[
        //     'payment' => 'required|in:cash,credit',
        //     'total' => 'required',
        //     'discount' => 'required',
        //     'grand_total' => 'required'
        // ]);
        $sale = $this->model::create(
            [
                'total' => $request->input('total'),
                'discount' => $request->input('discount'),
                'grand_total' => $request->input('grand_total'),
                'payment' => $request->input('payment'),
                'fk_user_id' => $request->user()->id,
                'timestamp' => Carbon::now('+5 hours'),
            ]
        );

        foreach ($request->input('sale_items') as $req) {
            $item = SaleItems::create(
                [
                    'total' => $req['total'],
                    'fk_inventory_id' => $req['inventory'],
                    'fk_sale_price_id' => $req['id'],
                    'fk_sale_id' => $sale->id,
                ]
            );

            foreach ($req['sale_item_purchased_qty'] as $qty) {
                $s_qty = PurchasedQuantity::create(
                    [
                        'qty' => $qty['qty'],
                        'fk_purchase_price_id' => $qty['stock_item_qty_history'],
                        'fk_sale_item_id' => $item->id,
                    ]
                );

                $m_qty = QuantityHistory::where('fk_purchase_price_id', $qty['stock_item_qty_history'])->first();
                if ($m_qty) {
                    $m_qty['qty'] = $m_qty['qty'] - $qty['qty'];
                    $m_qty->save();
                }
            }
        }
        return response()->json($sale, 200);
    }

    public function get_by_receipt_code(Request $request)
    {
        $code = $request->input('id');
        $data = $this->model::with('sale_items')->where('id', $code)->first();
        if ($data) {
            return response()->json($data, 200);
        } else {
            return response()->json('Not Found', 400);
        }
    }

    public function return_sale(Request $request)
    {
        // $this->validate($request,[
        //     'inventory'=>'required',
        //     'sale' => 'required',
        // ]);
        $total = $request->input('total');
        $saleId = $request->input('sale');
        foreach ($request['return_items'] as $req) {
            $return_item = ReturnItem::create(
                [
                    'fk_inventory_id' => $req['inventory'],
                    'fk_sale_id' => $req['sale'],
                    'fk_user_id' => $request->user()->id,
                    'total' => $req['total'],
                    'qty' => $req['total_qty'],
                ]
            );
            if ($return_item) {
                $sale_item = SaleItems::where('fk_sale_id', $req['sale'])
                    ->where('fk_inventory_id', $req['inventory'])
                    ->first();
                if ($sale_item) {
                    $sale_item->total = $sale_item->total - $req['total'];
                    $sale_item->save();
                }

                foreach ($req['returned_item_qty'] as $qty) {
                    $r_qty = ReturnQuantity::create(
                        [
                            'qty' => $qty['qty'],
                            'fk_returned_item_id' => $return_item->id,
                            'fk_purchase_price_id' => $qty['stock_item_qty_history'],
                        ]
                    );
                    if ($r_qty) {
                        $sale_qty = PurchasedQuantity::where('fk_sale_item_id', $sale_item->id)
                            ->where('fk_purchase_price_id', $qty['stock_item_qty_history'])
                            ->first();
                        if ($sale_qty) {
                            $sale_qty->qty = $sale_qty->qty - $qty['qty'];
                            $sale_qty->save();
                        }
                    }
                    $pur_qty = QuantityHistory::where('fk_purchase_price_id', $qty['stock_item_qty_history'])->first();
                    if ($pur_qty) {
                        $pur_qty->qty = $pur_qty->qty + $qty['qty'];
                        $pur_qty->save();
                    }
                }
            }
        }
        if ($return_item) {
            $sale = Sale::where('id', $saleId)->first();
            if ($sale) {
                if ($sale->total == $total) {
                    $sale->discount = 0;
                }
                if ($sale->grand_total < $total) {
                    $sale->grand_total = 0;
                } else {
                    $sale->grand_total = $sale->grand_total - $total;
                }
                $sale->total = $sale->total - $total;
                $sale->save();
            }
        }
        return response()->json($return_item, 200);
    }
}
