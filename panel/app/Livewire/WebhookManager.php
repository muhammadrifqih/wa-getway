<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Webhook;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WebhookManager extends Component
{
    public $url = '';
    public $secret = '';
    public $isActive = false;

    public function mount()
    {
        $webhook = Webhook::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'name' => 'Default Webhook',
                'url' => '',
                'events' => json_encode(['message.received']),
                'secret' => Str::random(32),
                'is_active' => false
            ]
        );
        $this->url = $webhook->url ?? '';
        $this->secret = $webhook->secret;
        $this->isActive = $webhook->is_active;
    }

    public function saveSettings()
    {
        $this->validate([
            'url' => 'nullable|url|max:255',
        ]);

        $webhook = Webhook::where('user_id', Auth::id())->first();
        if ($webhook) {
            $webhook->update([
                'url' => $this->url,
                'is_active' => $this->isActive
            ]);
            session()->flash('message', 'Webhook settings saved successfully.');
        }
    }

    public function generateNewSecret()
    {
        $webhook = Webhook::where('user_id', Auth::id())->first();
        if ($webhook) {
            $webhook->update(['secret' => Str::random(32)]);
            $this->secret = $webhook->secret;
            session()->flash('message', 'New secret generated.');
        }
    }

    public function render()
    {
        $webhook = Webhook::where('user_id', Auth::id())->first();
        $logs = [];
        if ($webhook) {
            $logs = WebhookLog::where('webhook_id', $webhook->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return view('livewire.webhook-manager', [
            'logs' => $logs
        ]);
    }
}
