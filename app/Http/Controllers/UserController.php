<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUserModels\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->middleware('auth');
        $this->user =  Auth::user();

    }

    public function loggedInUser()
    {
        return response()->json(auth()->user(), 200);
    }

    public function user_select_list()
    {
       $user = $this->model::get(['id', 'name']);
       return response()->json($user, 200);
    }
}
