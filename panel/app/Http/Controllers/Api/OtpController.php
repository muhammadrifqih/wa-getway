<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtpCode;
use App\Models\Message;
use App\Models\WhatsappDevice;
use App\Jobs\SendWhatsAppMessageJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
            'purpose' => 'required|string',
            'device_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = $request->user();

        // 1. Generate OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        OtpCode::create([
            'user_id' => $user->id,
            'phone' => $request->target,
            'purpose' => $request->purpose,
            'code_hash' => Hash::make($code),
            'expires_at' => $expiresAt
        ]);

        // 2. Cari Device
        $deviceQuery = WhatsappDevice::where('user_id', $user->id)->where('status', 'connected');
        if ($request->filled('device_id')) {
            $deviceQuery->where('id', $request->device_id);
        }
        $device = $deviceQuery->first();

        if (!$device) {
            return response()->json(['error' => 'No connected WhatsApp device found.'], 404);
        }

        // 3. Queue Pesan OTP
        $messageText = "*KODE OTP ANDA*\n\nKode OTP Anda adalah: *$code*\n\nKode berlaku selama 5 menit. Jangan berikan kode ini kepada siapa pun.";
        
        $message = Message::create([
            'user_id' => $user->id,
            'whatsapp_device_id' => $device->id,
            'target' => $request->target,
            'message' => $messageText,
            'type' => 'otp',
            'status' => 'pending'
        ]);

        SendWhatsAppMessageJob::dispatch($message->id);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent via WhatsApp.'
        ]);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|string',
            'purpose' => 'required|string',
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = $request->user();

        $otp = OtpCode::where('user_id', $user->id)
            ->where('phone', $request->target)
            ->where('purpose', $request->purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', 5)
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json(['error' => 'OTP not found or expired.'], 404);
        }

        $otp->increment('attempts');

        if (Hash::check($request->code, $otp->code_hash)) {
            $otp->update(['verified_at' => now()]);
            return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
        }

        return response()->json(['error' => 'Invalid OTP code.'], 400);
    }
}
