<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RefundController extends Controller
{
    //

    public function __invoke()
    {
        return Inertia::render('Refund/Index');
    }
}
