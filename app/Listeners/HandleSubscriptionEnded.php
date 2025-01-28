<?php

namespace App\Listeners;

use Laravel\Cashier\Cashier;
use App\Mail\SubscriptionEnded;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Cashier\Events\WebhookReceived;

class HandleSubscriptionEnded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        //
        if($event->payload['type'] !== 'customer.subscription.deleted'){
            return;
        }
        Mail::to($this->getUserByStripeId($event->payload['data']['object']['customer']))
        ->send(new SubscriptionEnded());
         
       
    }
    public function getUserByStripeId(string $stripeId)
    {
        return Cashier::findBillable($stripeId);
    }
}
