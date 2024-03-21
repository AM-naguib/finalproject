<?php

namespace App\Http\Controllers\Back;

use App\Models\Plan;
use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    public function pricing(){

        $plans = Plan::get();

        return view("back.dashboard.pricing", compact("plans"));
    }
}
