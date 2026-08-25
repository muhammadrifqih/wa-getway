<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WhatsappDevice;
use App\Models\Message;
use App\Models\Subscription;
use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Stats
        $deviceCount = WhatsappDevice::where('user_id', $user->id)->count();
        $connectedDeviceCount = WhatsappDevice::where('user_id', $user->id)->where('status', 'connected')->count();
        
        $subscription = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $messageCount = Message::where('user_id', $user->id)
            ->where('created_at', '>=', $subscription->started_at ?? now()->startOfMonth())
            ->count();

        $recentMessages = Message::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $webhook = Webhook::where('user_id', $user->id)->first();
        $webhookActive = $webhook && $webhook->is_active;

        return view('livewire.user-dashboard', [
            'deviceCount' => $deviceCount,
            'connectedDeviceCount' => $connectedDeviceCount,
            'messageCount' => $messageCount,
            'recentMessages' => $recentMessages,
            'subscription' => $subscription,
            'webhookActive' => $webhookActive
        ]);
    }
}
