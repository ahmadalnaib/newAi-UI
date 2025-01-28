<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'plan_id' => 'required|exists:plans,id'
            ]);

            // Get plan
            $plan = Plan::findOrFail($validated['plan_id']);

            // Create subscription
            return $request->user()
                ->newSubscription('default', $plan->price_id)
                //  ->trialDays(2)
                // ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('home'),
                    'metadata' => [
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'email' => $request->user()->email
                    ]
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}