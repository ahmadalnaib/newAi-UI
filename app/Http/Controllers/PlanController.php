<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    //
    public function index(){
       $plans=Plan::all();
        return Inertia::render('Plans/Index',[
            'plans'=>$plans
        ]);
  
    }
}
