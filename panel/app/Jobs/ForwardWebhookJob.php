<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\WebhookLog;

class ForwardWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $webhook;
    protected $payload;

    public function __construct($webhook, $payload)
    {
        $this->webhook = $webhook;
        $this->payload = $payload;
    }

    public function handle(): void
    {
        if (!$this->webhook->is_active || !$this->webhook->url) {
            return;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Webhook-Secret' => $this->webhook->secret,
                    'Authorization' => 'Bearer ' . $this->webhook->secret
                ])
                ->post($this->webhook->url, $this->payload);

            WebhookLog::create([
                'webhook_id' => $this->webhook->id,
                'event' => 'message.received',
                'payload' => json_encode($this->payload),
                'response_code' => $response->status(),
                'response_body' => $response->body(),
                'status' => $response->successful() ? 'success' : 'failed'
            ]);

            // Handle Webhook Auto-Reply Feature
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Jika server webhook merespon dengan JSON: {"reply": "Halo juga!"}
                if (is_array($responseData) && !empty($responseData['reply'])) {
                    $device = \App\Models\WhatsappDevice::where('session_id', $this->payload['session_id'])->first();
                    
                    if ($device) {
                        $message = \App\Models\Message::create([
                            'user_id' => $this->webhook->user_id,
                            'whatsapp_device_id' => $device->id,
                            'target' => $this->payload['sender'],
                            'message' => (string) $responseData['reply'],
                            'status' => 'pending'
                        ]);
                        
                        \App\Jobs\SendWhatsAppMessageJob::dispatch($message->id);
                    }
                }
            }
        } catch (\Exception $e) {
            WebhookLog::create([
                'webhook_id' => $this->webhook->id,
                'event' => 'message.received',
                'payload' => json_encode($this->payload),
                'response_code' => 500,
                'response_body' => $e->getMessage(),
                'status' => 'failed'
            ]);
            throw $e;
        }
    }
}
