<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        return view('back.dashboard/dashboard');
    }

    public function history(){

        $history = History::where("user_id", auth()->user()->id)->get();

        return view("back.dashboard.history", compact("history"));
    }
}
