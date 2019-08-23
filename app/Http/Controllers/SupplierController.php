<?php

namespace App\Http\Controllers;

use App\Models\AppSupplierModels\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    protected $model;
    public function __construct(Supplier $model)
    {
        $this->model = $model;
        $this->middleware('auth');
    }

    public function index()
    {
        $supplier = $this->model->orderBy('name')->paginate(25);
        return response()->json($supplier, 200);
    }

    public function select_list(Request $request)
    {
        $data = $this->model->get(['id', 'name']);
        return response()->json($data);

        // return response()->json(auth()->id());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'mobile' => 'required',

        ]);
        $supplier = $this->model->create($request->all());
        return response()->json($supplier, 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'sometimes|string',
            'mobile' => 'sometimes',

        ]);
        $supplier = $this->model::find($id);
        if ($supplier) {
            $supplier->update($request->all());
            return response()->json($supplier);
        };
        return response()->json(false);
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        if (strlen($name) > 1) {
            $supplier = $this->model::where('name', 'like',$name . '%');
            return response()->json($supplier->orderBy('name', 'desc')->get(['id', 'name']));
        }
        return response()->json(false, 404);

    }

    public function paginate_search(Request $request)
    {
        $name = $request->input('name');
            $supplier = $this->model::where('name', 'like', '%' . $name . '%');
            return response()->json($supplier->orderBy('name', 'desc')->paginate(25));
        }
        // return response()->json(false, 404);

}
