<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppHrModels\Employee;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employees = Employee::paginate(25);
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);

        $employee = Employee::create($request->all());
        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);

        $employee = Employee::findorFail($id);
        $employee->update($request->all());
        return response()->json($employee);
    }

    public function employee_select_list()
    {
        $employees = Employee::get();
        return response()->json($employees);
    }
}
