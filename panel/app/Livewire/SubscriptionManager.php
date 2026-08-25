<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class SubscriptionManager extends Component
{
    public function subscribe($planId)
    {
        $plan = Plan::findOrFail($planId);
        
        // In a real app, this would redirect to a payment gateway
        // For MVP, we'll just activate it directly
        
        Subscription::where('user_id', Auth::id())->update(['status' => 'expired']);
        
        Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
            'expired_at' => now()->addMonth(),
        ]);
        
        session()->flash('message', 'Successfully upgraded to ' . $plan->name);
    }

    public function render()
    {
        $subscription = Subscription::with('plan')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        $messageCount = 0;
        if ($subscription) {
            $messageCount = Message::where('user_id', Auth::id())
                ->where('created_at', '>=', $subscription->started_at ?? now()->startOfMonth())
                ->count();
        }

        $plans = Plan::where('is_active', true)->get();

        return view('livewire.subscription-manager', [
            'subscription' => $subscription,
            'messageCount' => $messageCount,
            'plans' => $plans
        ]);
    }
}
