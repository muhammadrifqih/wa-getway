<div wire:poll.3s>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            WhatsApp Devices
        </h2>
    </div>

    <!-- Add Device Form -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
        <form wire:submit.prevent="addDevice" class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="deviceName" class="block text-sm font-medium text-gray-700">Device Name</label>
                <input type="text" wire:model="deviceName" id="deviceName" placeholder="e.g. WhatsApp Toko" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('deviceName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Add WhatsApp
            </button>
        </form>
    </div>

    <!-- Devices List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($devices as $device)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg text-gray-800">{{ $device->name }}</h3>
                    <p class="text-sm text-gray-500 font-mono">{{ $device->phone ?? 'Unknown Number' }}</p>
                </div>
                <button wire:click="deleteDevice({{ $device->id }})" wire:confirm="Are you sure?" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 flex items-center justify-center mb-4">
                @if($device->status === 'waiting_qr')
                    @if(isset($qrCodes[$device->session_id]))
                        <img src="{{ $qrCodes[$device->session_id] }}" alt="QR Code" class="w-48 h-48 border rounded p-2">
                    @else
                        <div class="animate-pulse flex flex-col items-center">
                            <div class="w-48 h-48 bg-gray-200 rounded"></div>
                            <span class="text-xs text-gray-400 mt-2">Loading QR...</span>
                        </div>
                    @endif
                @elseif($device->status === 'connected')
                    <div class="flex flex-col items-center text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium text-lg">Connected</span>
                    </div>
                @elseif($device->status === 'initializing')
                    <div class="flex flex-col items-center text-blue-500">
                        <svg class="animate-spin h-10 w-10 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm">Initializing...</span>
                    </div>
                @else
                    <div class="flex flex-col items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium capitalize">{{ str_replace('_', ' ', $device->status) }}</span>
                    </div>
                @endif
            </div>

            <div class="border-t pt-4 text-xs text-gray-500 flex justify-between items-center">
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full {{ $device->status === 'connected' ? 'bg-green-500' : ($device->status === 'waiting_qr' ? 'bg-yellow-400' : 'bg-gray-400') }}"></span>
                    <span class="capitalize">{{ str_replace('_', ' ', $device->status) }}</span>
                </div>
                <span>Last connect: {{ $device->last_connected_at ? \Carbon\Carbon::parse($device->last_connected_at)->diffForHumans() : 'Never' }}</span>
            </div>
        </div>
        @endforeach

        @if($devices->isEmpty())
        <div class="col-span-full bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">No Devices Connected</h3>
            <p class="mt-1 text-gray-500">Get started by adding your first WhatsApp device to scan the QR code.</p>
        </div>
        @endif
    </div>
</div>
