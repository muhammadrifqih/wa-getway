<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Webhook Settings
        </h2>
        <p class="text-sm text-gray-500 mt-1">Configure webhooks to receive real-time notifications about incoming messages.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Configuration Form -->
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <form wire:submit.prevent="saveSettings">
                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-gray-700">Webhook URL</label>
                    <input type="url" wire:model="url" id="url" placeholder="https://yourwebsite.com/webhook" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Enable Webhook</span>
                    </label>
                </div>

                <div class="mb-6 pt-4 border-t border-gray-100">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                    <p class="text-xs text-gray-500 mb-2">Used to verify the HMAC SHA-256 signature of incoming payloads.</p>
                    <div class="flex items-center gap-2" x-data="{ copied: false, copyToClipboard() { navigator.clipboard.writeText('{{ $secret }}').then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }) } }">
                        <code class="px-2 py-1 bg-gray-100 rounded text-gray-800 font-mono text-sm flex-1 truncate">{{ $secret }}</code>
                        <button type="button" @click="copyToClipboard()" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 py-1 px-2 rounded font-medium" title="Copy to clipboard">
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" x-cloak class="text-green-600">Copied!</span>
                        </button>
                        <button type="button" wire:click="generateNewSecret" wire:confirm="Are you sure? Old signatures will fail." class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-2 rounded font-medium">
                            Regenerate
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    Save Configuration
                </button>
            </form>
        </div>

        <!-- Recent Logs -->
        <div wire:poll.5s class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-medium text-gray-900">Recent Deliveries</h3>
            </div>
            <div class="overflow-y-auto max-h-[500px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payload</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('M d, H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->response_code >= 200 && $log->response_code < 300 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $log->response_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono text-xs max-w-xs truncate">
                                {{ $log->payload }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                No webhook deliveries yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
