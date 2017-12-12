<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){


        return view('settings.user.list', [
            'users' => User::all()
        ]);
    }
}
