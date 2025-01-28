<?php

namespace App\Listeners;


use Laravel\Cashier\Cashier;
use App\Mail\SubscriptionCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Cashier\Events\WebhookReceived;
use App\Subscription\StripeSubscriptionDecorator;


class HandleSubscriptionCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }



    public function handle(WebhookReceived $event): void
    {
        try {
            if ($event->payload['type'] !== 'customer.subscription.created') {
                return;
            }

            $subscription = $event->payload['data']['object'];
            $user = $this->getUserByStripeId($subscription['customer']);

            // Get latest invoice directly from subscription data
            $latestInvoiceId = $subscription['latest_invoice'];

            $invoice = $user->findInvoice($latestInvoiceId);


            if (!$invoice) {
                Log::warning('Invoice not found', [
                    'subscription_id' => $subscription['id'],
                    'invoice_id' => $latestInvoiceId
                ]);
                return;
            }

            $invoiceUrl = route('membership.invoice', ['invoice' => $invoice->id]);

            // Check if the user has a subscription
            $userSubscription = $user->subscription();
            if (!$userSubscription) {
                Log::error('User does not have an active subscription', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription['id']
                ]);
                return;
            }

            $stripeSubscription = $user->subscription()->asStripeSubscription();
            $decorator = new StripeSubscriptionDecorator($stripeSubscription);
            $planDetails = [
                'name' => $decorator->planName(),
                'currency' => $decorator->currency(),
                'amount' => number_format($invoice->rawTotal() / 100, 2)
            ];



            Mail::to($user->email)
                ->send(new SubscriptionCreated(
                    $user->name,
                    $invoiceUrl,
                    $planDetails
                ));
        } catch (\Exception $e) {
            Log::error('Subscription creation processing failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription['id'] ?? null
            ]);
            throw $e;
        }
    }

    public function getUserByStripeId(string $stripeId)
    {
        return Cashier::findBillable($stripeId);
    }
}
