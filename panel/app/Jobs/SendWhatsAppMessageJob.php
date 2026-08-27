<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $messageId;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public function handle(): void
    {
        $message = Message::with('device')->find($this->messageId);
        if (!$message || $message->status !== 'pending') {
            return;
        }

        $device = $message->device;
        if (!$device || $device->status !== 'connected') {
            $message->update(['status' => 'failed', 'error_message' => 'Device not connected']);
            return;
        }

        $waServiceUrl = env('WA_SERVICE_URL', 'http://localhost:3000');
        $waServiceKey = env('WA_SERVICE_KEY');

        try {
            $payload = [
                'target' => $message->target,
                'message' => $message->message,
            ];

            if ($message->type === 'media' && $message->media_url) {
                $payload['media_url'] = $message->media_url;
                $payload['media_name'] = $message->media_name;
                $payload['media_mimetype'] = $message->media_mimetype;
            }
            
            if ($message->metadata) {
                $payload['metadata'] = $message->metadata;
            }

            $response = Http::withHeaders([
                'X-SERVICE-KEY' => $waServiceKey
            ])->post("{$waServiceUrl}/internal/sessions/{$device->session_id}/send", $payload);

            if ($response->successful()) {
                $message->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    // Optionally save Baileys message_id here if returned
                ]);
            } else {
                $message->update([
                    'status' => 'failed',
                    'error_message' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WA Job Error: ' . $e->getMessage());
            $message->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            throw $e; // Trigger retry
        }
    }
}
