<?php

namespace App\Http\Controllers;

use App\Models\TailwindCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComponentController extends Controller
{
    //

    public function index()
    {
        $designs = TailwindCode::where('user_id', 1)->get();
        return Inertia::render('Design/Index', [
            'designs' => $designs
        ]);
    }


    public function show(TailwindCode $tailwindCode)
    {
        return Inertia::render('Design/Show', [
            'tailwindCode' => $tailwindCode
        ]);
    }
}
