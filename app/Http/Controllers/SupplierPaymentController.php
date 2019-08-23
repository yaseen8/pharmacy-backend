<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\AppSupplierModels\SupplierPayment;
use App\Models\AppSupplierModels\SupplierPaymentImage;

class SupplierPaymentController extends Controller
{

    protected $model;

    public function __construct(SupplierPayment $model)
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
        $data = $this->model::with('supplier_payment_image', 'supplier')->orderBy('timestamp', 'DESC')->paginate(25);
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'total_amount' => 'required|numeric',
            'amount' => 'required|numeric',
            'balance' => 'required|numeric',
            'payment_via' => 'required|string',
            'supplier' => 'required|exists:supplier,id',
        ]);

        $destination_path = null;
        if ($request->hasFile('supplier_payment_cheque_image')) {
            $picName = $request->file('supplier_payment_cheque_image')->hashName();

            $destination_path = 'upload/' . $request->user()->username . '/supplier/' . $request->supplier;
            $disk_name=env('DISK');
            $disk=Storage::disk($disk_name);
            if($disk_name == 'gcs'){
                $disk->putFileAs($destination_path, $request->file('supplier_payment_cheque_image'),$picName,'public');
            }else{
                $request->file('supplier_payment_cheque_image')->move($destination_path, $picName);

            }
        }

            $obj = $this->model->create([
                'total_amount' => $request->total_amount,
                'amount' => $request->amount,
                'balance' => $request->balance,
                'payment_via' => $request->payment_via,
                'fk_supplier_id' => $request->supplier,
                'fk_user_id' => $request->user()->id
            ]);

            if($destination_path)
            {
                $data = SupplierPaymentImage::create([
                    'img' => $destination_path . '/' . $picName,
                    'fk_payment_id' => $obj->id
                ]);
            }

            return response()->json($obj, 200);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function paginate_search(Request $request)
    {
        $name = $request->input('name');
            $payment = $this->model::with('supplier', 'supplier_payment_image')
            ->whereHas('supplier', function($q){
                $q->where('name', 'like', '%' . \Request::input('name') . '%');
            })
            // ->orderBy('name', 'desc')
            ->paginate(25);
            return response()->json($payment);
    }
}
