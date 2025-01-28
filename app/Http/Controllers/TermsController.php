<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TermsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Terms/Index');
        
    }
}
