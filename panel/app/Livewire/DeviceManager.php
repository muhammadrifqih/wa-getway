<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WhatsappDevice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class DeviceManager extends Component
{
    public $deviceName = '';
    public $qrCodes = [];

    public function addDevice()
    {
        $this->validate([
            'deviceName' => 'required|string|max:255'
        ]);

        $sessionId = 'session-user-' . Auth::id() . '-' . uniqid();

        $device = WhatsappDevice::create([
            'user_id' => Auth::id(),
            'name' => $this->deviceName,
            'session_id' => $sessionId,
            'status' => 'initializing'
        ]);

        $this->deviceName = '';

        // Call Node.js
        $waServiceUrl = env('WA_SERVICE_URL', 'http://localhost:3000');
        $waServiceKey = env('WA_SERVICE_KEY');

        Http::withHeaders(['X-SERVICE-KEY' => $waServiceKey])
            ->post("{$waServiceUrl}/internal/sessions/create", [
                'sessionId' => $sessionId
            ]);
    }

    public function deleteDevice($id)
    {
        $device = WhatsappDevice::where('user_id', Auth::id())->findOrFail($id);
        
        $waServiceUrl = env('WA_SERVICE_URL', 'http://localhost:3000');
        $waServiceKey = env('WA_SERVICE_KEY');

        Http::withHeaders(['X-SERVICE-KEY' => $waServiceKey])
            ->delete("{$waServiceUrl}/internal/sessions/{$device->session_id}");

        $device->delete();
    }

    public function checkStatus($sessionId)
    {
        $waServiceUrl = env('WA_SERVICE_URL', 'http://localhost:3000');
        $waServiceKey = env('WA_SERVICE_KEY');

        $response = Http::withHeaders(['X-SERVICE-KEY' => $waServiceKey])
            ->get("{$waServiceUrl}/internal/sessions/{$sessionId}/status");

        if ($response->successful()) {
            $data = $response->json();
            $device = WhatsappDevice::where('session_id', $sessionId)->first();
            if ($device && $device->status !== $data['status']) {
                $device->update([
                    'status' => $data['status'],
                    'phone' => $data['phone'] ?? $device->phone,
                    'last_connected_at' => $data['status'] === 'connected' ? now() : $device->last_connected_at
                ]);
            }
        }
    }

    public function fetchQr($sessionId)
    {
        $waServiceUrl = env('WA_SERVICE_URL', 'http://localhost:3000');
        $waServiceKey = env('WA_SERVICE_KEY');

        $response = Http::withHeaders(['X-SERVICE-KEY' => $waServiceKey])
            ->get("{$waServiceUrl}/internal/sessions/{$sessionId}/qr");

        if ($response->successful()) {
            $this->qrCodes[$sessionId] = $response->json()['qr'] ?? null;
        }
    }

    public function render()
    {
        $devices = WhatsappDevice::where('user_id', Auth::id())->get();

        foreach ($devices as $device) {
            $this->checkStatus($device->session_id);
            if ($device->status === 'waiting_qr') {
                $this->fetchQr($device->session_id);
            } else {
                unset($this->qrCodes[$device->session_id]);
            }
        }

        // Refetch after status updates
        $devices = WhatsappDevice::where('user_id', Auth::id())->get();

        return view('livewire.device-manager', [
            'devices' => $devices
        ]);
    }
}
