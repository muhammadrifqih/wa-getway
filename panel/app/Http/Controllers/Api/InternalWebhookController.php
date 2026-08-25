<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappDevice;
use App\Models\Webhook;
use App\Jobs\ForwardWebhookJob;
use Illuminate\Support\Facades\Log;

class InternalWebhookController extends Controller
{
    public function receive(Request $request)
    {
        $serviceKey = env('WA_SERVICE_KEY', 'secret_service_key');
        if ($request->header('X-SERVICE-KEY') !== $serviceKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $sessionId = $request->input('session_id');
        $device = WhatsappDevice::where('session_id', $sessionId)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $webhook = Webhook::where('user_id', $device->user_id)->first();

        if ($webhook && $webhook->is_active && $webhook->url) {
            $payload = $request->all();
            
            // Add signature if secret exists
            if ($webhook->secret) {
                $payload['signature'] = hash_hmac('sha256', json_encode($payload), $webhook->secret);
            }

            ForwardWebhookJob::dispatch($webhook, $payload);
        }

        return response()->json(['success' => true]);
    }
}
