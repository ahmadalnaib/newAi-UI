<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    // check if user has stripe id
    public function updated(User $user)
    {
        // sync user's stripe customer
        if($user->hasStripeId()){
         $user->syncStripeCustomerDetails();
        }
    }
}
