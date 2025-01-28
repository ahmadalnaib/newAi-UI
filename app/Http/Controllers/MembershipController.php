<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Subscription\StripeSubscriptionDecorator;

class MembershipController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = auth()->user();
        $subscription = $user->subscription();
        $endsAt = $subscription ? $subscription->ends_at : null;

        return Inertia::render('Membership/Index', [
            'isSubscribed' => $user->subscribed(),
            'isCancelled' => $user->subscription() && $user->subscription()->canceled(),
            'end_at' => $endsAt ? $endsAt->toDateString() : null,
            'diffHuman' => $endsAt ? $endsAt->diffForHumans() : null,
            'paymentNotSuccessful' => $user->hasIncompletePayment(),
            'plan' => $user->subscribed()
                ? [
                    'currency' => (new StripeSubscriptionDecorator($user->subscription()->asStripeSubscription()))->currency(),
                    'planName' => (new StripeSubscriptionDecorator($user->subscription()->asStripeSubscription()))->planName(),
                ]
                : null,
            'upcoming' => $user->upcomingInvoice()
                ? [
                    'date' => $user->upcomingInvoice()->date()->toDateString(),
                    'diff' => $user->upcomingInvoice()->date()->diffForHumans(),
                    'total' => $user->upcomingInvoice()->total(),
                    // Add other necessary fields here
                ]
                : null,
                'invoices'=> $user->invoicesIncludingPending()->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'date' => $invoice->date()->toDateString(),
                        'total' => $invoice->total(),
                      
                        // Add other necessary fields here
                    ];
                }),             
        ]);
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('membership.index'));
    }

    public function resume(Request $request)
    {
        auth()->user()->subscription()->resume();

        return redirect()->route('membership.index');
    }

    public function cancel(Request $request)
    {
        auth()->user()->subscription()->cancel();

        return redirect()->route('membership.index');
    }


    public function invoice(Request $request)
    {
        return auth()->user()->downloadInvoice($request->invoice);
    }
}