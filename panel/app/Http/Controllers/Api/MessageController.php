<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\WhatsappDevice;
use App\Jobs\SendWhatsAppMessageJob;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
            'message' => 'required|string',
            'device_id' => 'nullable|integer',
            'media_url' => 'nullable|url',
            'media_name' => 'nullable|string',
            'media_mimetype' => 'nullable|string',
            'poll_name' => 'nullable|string',
            'poll_options' => 'nullable|array|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = $request->user();

        // Cari device
        $deviceQuery = WhatsappDevice::where('user_id', $user->id)
            ->where('status', 'connected');

        if ($request->filled('device_id')) {
            $deviceQuery->where('id', $request->device_id);
        }

        $device = $deviceQuery->first();

        if (!$device) {
            return response()->json(['error' => 'No connected WhatsApp device found.'], 404);
        }

        // Check quota (Phase 11)
        $subscription = \App\Models\Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->first();

        if (!$subscription) {
            return response()->json(['error' => 'No active subscription found. Please upgrade your plan.'], 403);
        }

        $messageCount = \App\Models\Message::where('user_id', $user->id)
            ->where('created_at', '>=', $subscription->started_at ?? now()->startOfMonth())
            ->count();

        if ($subscription->plan->max_messages > 0 && $messageCount >= $subscription->plan->max_messages) {
            return response()->json(['error' => 'Message quota exceeded for your current plan.'], 403);
        }
        $isPoll = $request->filled('poll_name') && $request->filled('poll_options');
        
        $message = Message::create([
            'user_id' => $user->id,
            'whatsapp_device_id' => $device->id,
            'target' => $request->target,
            'message' => $request->message,
            'type' => $isPoll ? 'poll' : ($request->filled('media_url') ? 'media' : 'text'),
            'media_url' => $request->media_url,
            'media_name' => $request->media_name,
            'media_mimetype' => $request->media_mimetype,
            'metadata' => $isPoll ? [
                'poll_name' => $request->poll_name,
                'poll_options' => $request->poll_options
            ] : null,
            'status' => 'pending'
        ]);

        // Dispatch Job
        SendWhatsAppMessageJob::dispatch($message->id);

        return response()->json([
            'success' => true,
            'message' => 'Message queued for sending.',
            'data' => $message
        ]);
    }

    public function index(Request $request)
    {
        $messages = Message::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return response()->json($messages);
    }
}
