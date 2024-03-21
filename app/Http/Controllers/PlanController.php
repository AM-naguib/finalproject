<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $plans = Plan::all();

        return view('back.dashboard.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.dashboard.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|numeric",
            "currency" => "required|string",
            "features" => "required",

        ]);
        Plan::create($data);
        return  response()->json(["message" => "Plan created successfully"], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('back.dashboard.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|numeric",
            "currency" => "required|string",
            "features" => "required",

        ]);
        $plan->update($data);
        return redirect()->route("admin.plans.index")->with("success", "Plan updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json(["message" => "Plan deleted successfully"], 200);
    }
}
