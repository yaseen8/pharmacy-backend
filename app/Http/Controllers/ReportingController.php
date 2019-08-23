<?php

namespace App\Http\Controllers;

use App\Models\AddInventoryModels\Inventory;
use App\Models\AddInventoryModels\QuantityHistory;
use App\Models\AddInventoryModels\StockHistory;
use App\Models\AddInventoryModels\StockItemQtyHistory;
use App\Models\AppDisposeModels\Dispose;
use App\Models\AppExpiryModels\ReturnExpiry;
use App\Models\AppExpiryModels\ReturnExpiryItem;
use App\Models\AppReturnModels\Config;
use App\Models\AppReturnModels\ReturnItem;
use App\Models\AppSaleModels\Sale;
use App\Models\AppSaleModels\SaleItems;
use App\Models\AppSupplierModels\SupplierPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    protected $model;
    public function __construct(Sale $model)
    {
        $this->model = $model;
        $this->middleware('auth');
    }

    public function sale_get_by_date(Request $request)
    {
        $month = $request->input('month');
        $day = $request->input('day');
        $current_year = date('Y');
        $mon = date('M');
        $result = array();
        for ($i = 1; $i <= $day; $i++) {
            $date = $current_year . '-' . $month . '-' . $i;
            $count_perday = Sale::whereDate('timestamp', $date)->sum('grand_total'); // counting number of sales per day
            $result[] = array(
                'name' => $mon . ' ' . $i,
                'value' => $count_perday); // adding into array
        }
        return response()->json($result, 200);
    }

    public function sale_report(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');

        $sale = $this->model::with('sale_items', 'user')->whereBetween('timestamp', [$from, $to])->get();
        return response()->json($sale, 200);
    }

    public function user_sale_report(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $user = $request->input('user');

        $sale = $this->model::with('sale_items', 'user')->whereBetween('timestamp', [$from, $to])
            ->where('fk_user_id', $user)
            ->get();
        return response()->json($sale, 200);
    }

    public function product_sale_report(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $sale = SaleItems::with('sale')
            ->where('fk_inventory_id', $request->input('product'))
            ->whereHas('sale', function ($query) {
                $query->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->get();
        return response()->json($sale, 200);
    }

    public function user_product_sale(Request $request)
    {
        $sale = SaleItems::with('sale')
            ->where('fk_inventory_id', $request->input('product'))
            ->whereHas('sale', function ($query) {
                $query->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')])
                    ->where('fk_user_id', \Request::input('user'));
            })
            ->get();
        return response()->json($sale, 200);
    }

    public function invoice_sale(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $sale = $this->model::with('sale_items', 'user')->whereBetween('id', [$from, $to])->get();
        return response()->json($sale, 200);
    }

    public function return_report(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');

        $sale = ReturnItem::with('inventory', 'user')->whereBetween('timestamp', [$from, $to])->get();
        return response()->json($sale, 200);
    }

    public function return_by_user(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $user = $request->input('user');

        $return = ReturnItem::with('inventory', 'user')->whereBetween('timestamp', [$from, $to])
            ->where('fk_user_id', $user)
            ->get();
        return response()->json($return, 200);
    }

    public function supplier_stock_report(Request $request)
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $supplier = $request->input('supplier');
        $stock = StockItemQtyHistory::with('stock_history', 'inventory')
            ->where('fk_supplier_id', $supplier)
            ->whereHas('stock_history', function ($query) {
                $query->whereBetween('date', [\Request::input('date_from'), \Request::input('date_to')])
                    ->orderBy('date', 'DESC');
            })
            ->get();
        return response()->json($stock, 200);
    }

    public function sale_profit(Request $request)
    {
        $data = array();
        $total_profit = 0;
        $discount = 0;
        $self_profit = 0;
        $profit = $this->model::with('sale_items')->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        foreach ($profit as $p) {
            foreach ($p['sale_items'] as $s) {
                $sale_price = 0;
                $sale_price = (float) $s['sale_price']['price'];
                foreach ($s['purchased_quantity'] as $q) {
                    $qty = 0;
                    $p_price = 0;
                    $tot_pur = 0;
                    $tot_sale = 0;
                    $tot = 0;
                    $qty = $qty + $q['qty'];
                    $p_price = (float) $q['purchase_price'][0]['price'];
                    $tot_pur = $qty * $p_price;
                    $tot_sale = $qty * $sale_price;
                    $tot = $tot_sale - $tot_pur;
                    $total_profit = $total_profit + $tot;
                    $self_profit = $self_profit + $tot;
                }

            }
            $self_profit = $self_profit - $p['discount'];
            $discount = $discount + $p['discount'];
        }
        $data = [
            'total' => $total_profit,
            'discount' => $discount,
            'self' => $self_profit,
        ];
        return response()->json($data, 200);
    }

    public function product_sale_profit(Request $request)
    {
        $total_profit = 0;
        $profit = SaleItems::with('sale')
            ->where('fk_inventory_id', $request->input('inventory'))
            ->whereHas('sale', function ($query) {
                $query->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->get();
        foreach ($profit as $p) {
            $sale_price = 0;
            $sale_price = (float) $p['sale_price']['price'];
            foreach ($p['purchased_quantity'] as $q) {
                $qty = 0;
                $p_price = 0;
                $tot_pur = 0;
                $tot_sale = 0;
                $tot = 0;
                $qty = $qty + $q['qty'];
                $p_price = (float) $q['purchase_price'][0]['price'];
                $tot_pur = $qty * $p_price;
                $tot_sale = $qty * $sale_price;
                $tot = $tot_sale - $tot_pur;
                $total_profit = $total_profit + $tot;
            }

        }
        return response()->json($total_profit, 200);
    }

    public function payment_report(Request $request)
    {
        $payment = SupplierPayment::with('supplier', 'user')->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        return response()->json($payment, 200);
    }

    public function supplier_payment_report(Request $request)
    {
        $payment = SupplierPayment::with('user')->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->where('fk_supplier_id', $request->input('supplier'))
            ->get();
        return response()->json($payment, 200);
    }

    public function company_stock(Request $request)
    {
        $stock = Inventory::where('fk_company_id', $request->input('company'))->get();
        return response()->json($stock, 200);
    }

    public function purchase_by_user(Request $request)
    {
        $stock = StockHistory::whereBetween('date', [$request->input('date_from'), $request->input('date_to')])
            ->where('fk_user_id', $request->input('user'))
            ->orderBy('date', 'DESC')
            ->get();
        return response()->json($stock, 200);
    }

    public function product_purchase(Request $request)
    {
        $purchase = StockItemQtyHistory::with('stock_history', 'supplier')
            ->where('fk_inventory_id', $request->input('inventory'))
            ->whereHas('stock_history', function ($q) {
                $q->whereBetween('date', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->get();
        return response()->json($purchase, 200);
    }

    public function expiry_return(Request $request)
    {
        $expiry = ReturnExpiry::with('expiry_items', 'supplier', 'user')->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        return response()->json($expiry, 200);
    }

    public function product_expiry_return(Request $request)
    {
        $expiry = ReturnExpiryItem::with('expiry_return')
            ->where('fk_inventory_id', $request->input('product'))
            ->whereHas('expiry_return', function ($q) {
                $q->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->with([
                'expiry_return' => function ($query) {
                    $query->with('supplier', 'user');
                },
            ])
            ->get();
        return response()->json($expiry, 200);
    }

    public function user_expiry_return(Request $request)
    {
        $expiry = ReturnExpiry::with('expiry_items', 'supplier')->where('fk_user_id', $request->input('user'))
            ->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        return response()->json($expiry, 200);
    }

    public function supplier_expiry_return(Request $request)
    {
        $expiry = ReturnExpiry::with('expiry_items', 'user')->where('fk_supplier_id', $request->input('supplier'))
            ->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        return response()->json($expiry, 200);
    }

    public function dispose_items_report(Request $request)
    {
        $dispose = Dispose::with('inventory', 'user')
            ->whereBetween('timestamp', [$request->input('date_from'), $request->input('date_to')])
            ->get();
        return response()->json($dispose, 200);
    }

    public function last_six_month()
    {
        dd(date('n'));
        $sale = Sale::where(DB::raw('MONTH(timestamp)'), '=', date('n'))->get();
        return response()->json($sale, 200);
    }

    public function company_sale(Request $request)
    {

        $total_profit = 0;
        $total_sale = 0;
        $data = array();

        $sale = SaleItems::with('sale', 'inventory')
            ->whereHas('sale', function ($query) {
                $query->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->whereHas('inventory', function ($q) {
                $q->where('fk_company_id', \Request::input('company'));
            })
            ->get();

        foreach ($sale as $s) {
            $total_sale = $total_sale + (float) ($s['total']);
        }
        //Total Company Profit

        $profit = SaleItems::with('sale', 'inventory')
            ->whereHas('sale', function ($query) {
                $query->whereBetween('timestamp', [\Request::input('date_from'), \Request::input('date_to')]);
            })
            ->whereHas('inventory', function ($q) {
                $q->where('fk_company_id', \Request::input('company'));
            })
            ->get();
        foreach ($profit as $p) {
            $sale_price = 0;
            $sale_price = (float) $p['sale_price']['price'];
            foreach ($p['purchased_quantity'] as $q) {
                $qty = 0;
                $p_price = 0;
                $tot_pur = 0;
                $tot_sale = 0;
                $tot = 0;
                $qty = $qty + $q['qty'];
                $p_price = (float) $q['purchase_price'][0]['price'];
                $tot_pur = $qty * $p_price;
                $tot_sale = $qty * $sale_price;
                $tot = $tot_sale - $tot_pur;
                $total_profit = $total_profit + $tot;
            }

        }

        $data = [
            'sale' => $total_sale,
            'profit' => $total_profit,
        ];
        return response()->json($data, 200);
    }

    public function get_dashboard_data()
    {
        $total = array();
        // Check total register inventory
        $inventory = count(Inventory::get());
        // Check Total Item near to expire
        $curr = Config::first();
        $date = (integer) $curr->notify;
        $t = Carbon::today();
        $new = $t->addMonths($date);
        $expireDate = $new->year . '-' . $new->month . '-' . $new->day;
        $expiry = count(QuantityHistory::whereDate('expiry', '<=', $expireDate)->where('qty', '>', 0)->get());
        //check product near to end

        $data = array();
        $qty = Config::first()->check_qty;
        $product = Inventory::with('quantity_history')->get();
        foreach ($product as $i) {
            $check = 0;
            foreach ($i['quantity_history'] as $q) {
                $check = $check + (int) ($q['qty']);
            }
            if ($check < $qty) {
                $data[] = $i;
            }
        }

        //Total stock amount

        $total_amount = 0;
        $total_stock = QuantityHistory::with('purchase_price_history')->get();
        foreach ($total_stock as $amount) {
            $tot = 0;
            $tot = $amount['qty'] * $amount['purchase_price_history']['price'];
            $total_amount = $total_amount + $tot;
        }

        $total = [
            'inventory' => $inventory,
            'expiry' => $expiry,
            'qty' => count($data),
            'amount' => $total_amount
        ];

        return response()->json($total, 200);
    }

}
