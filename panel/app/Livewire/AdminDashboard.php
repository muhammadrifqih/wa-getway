<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use App\Models\WhatsappDevice;
use App\Models\Subscription;

class AdminDashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $totalDevices = WhatsappDevice::count();
        $connectedDevices = WhatsappDevice::where('status', 'connected')->count();
        $totalMessages = Message::count();
        $sentMessages = Message::where('status', 'sent')->count();
        
        $recentUsers = User::latest()->limit(5)->get();

        return view('livewire.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalDevices' => $totalDevices,
            'connectedDevices' => $connectedDevices,
            'totalMessages' => $totalMessages,
            'sentMessages' => $sentMessages,
            'recentUsers' => $recentUsers
        ]);
    }
}
