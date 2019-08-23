<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\AppCompanyModels\Company;

class CompanyController extends Controller
{

    protected $model;
    public function __construct(Company $model)
    {
        $this->model=$model;
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compnay=$this->model->orderBy('name')->paginate(25);
        return response()->json($compnay, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string',

        ]);
        $company=$this->model->create($request->all());
        return response()->json($company, 201);
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
        $this->validate($request,[
            'name'=>'sometimes|string',

        ]);
        $company = $this->model::find($id);
        if($company){
            $company->update($request->all());
            return response()->json($company);
        };
        return response()->json(false);
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

    public function select_list()
    {
        $data=$this->model->get(['id','name']);
        return response()->json($data);
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        if (strlen($name) > 1) {
            $company = $this->model::where('name', 'like', $name . '%');
            return response()->json($company->orderBy('name', 'desc')->get());
        }
        return response()->json(false, 404);

    }

    public function paginate_search(Request $request)
    {
        $name = $request->input('name');
        if (strlen($name) > 1) {
            $company = $this->model::where('name', 'like', '%' . $name . '%');
            return response()->json($company->orderBy('name', 'desc')->paginate(25));
        }
        return response()->json(false, 404);

    }
}
