<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiKeyManager extends Component
{
    public $keyName = '';
    public $newlyCreatedKey = null;

    public function createKey()
    {
        $this->validate([
            'keyName' => 'required|string|max:255'
        ]);

        $rawKey = 'wa_live_' . Str::random(32);
        
        $apiKey = ApiKey::create([
            'user_id' => Auth::id(),
            'name' => $this->keyName,
            'key_prefix' => substr($rawKey, 0, 12) . '***',
            'key_hash' => hash('sha256', $rawKey),
            'is_active' => true,
        ]);

        $this->keyName = '';
        $this->newlyCreatedKey = $rawKey; // ONLY shown once!
    }

    public function deleteKey($id)
    {
        ApiKey::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->newlyCreatedKey = null;
    }

    public function toggleKey($id)
    {
        $key = ApiKey::where('user_id', Auth::id())->findOrFail($id);
        $key->is_active = !$key->is_active;
        $key->save();
        $this->newlyCreatedKey = null;
    }

    public function render()
    {
        return view('livewire.api-key-manager', [
            'apiKeys' => ApiKey::where('user_id', Auth::id())->latest()->get()
        ]);
    }
}
