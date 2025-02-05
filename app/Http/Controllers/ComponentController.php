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
        $designs = TailwindCode::all();
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
