<?php

namespace App\Subscription;

use App\Models\Plan;
use Stripe\Subscription;

class StripeSubscriptionDecorator
{
  public function __construct(protected Subscription $subscription) {}

  public function planName()
  {
     return $this->planFromPriceId($this->subscription->plan->id)['name'];
  }
  
  public function currency()
  {
    return strtoupper($this->subscription->currency);
  }

  protected function planFromPriceId(string $priceId)
  {
    return once(function() use ($priceId) {
      return Plan::where('price_id', $priceId)->first();
    });
  }
}
