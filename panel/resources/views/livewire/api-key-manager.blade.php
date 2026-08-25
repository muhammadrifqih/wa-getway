<div wire:poll.10s>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            API Keys
        </h2>
        <p class="text-sm text-gray-500 mt-1">Manage your API keys for programmatic access to the gateway.</p>
    </div>

    <!-- Alert for Newly Created Key -->
    @if($newlyCreatedKey)
        <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">New API Key Created!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Please copy this key and store it safely. For security reasons, <strong>it will not be shown again</strong>.</p>
                        <div class="mt-3 flex items-center gap-2">
                            <code class="px-3 py-2 bg-green-100 rounded text-green-900 font-mono text-lg select-all">{{ $newlyCreatedKey }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Add API Key Form -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
        <form wire:submit.prevent="createKey" class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="keyName" class="block text-sm font-medium text-gray-700">Key Name</label>
                <input type="text" wire:model="keyName" id="keyName" placeholder="e.g. Website Production" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('keyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                + Create Key
            </button>
        </form>
    </div>

    <!-- API Keys List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prefix</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Used</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($apiKeys as $key)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $key->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $key->key_prefix }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $key->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $key->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="toggleKey({{ $key->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            {{ $key->is_active ? 'Disable' : 'Enable' }}
                        </button>
                        <button wire:click="deleteKey({{ $key->id }})" wire:confirm="Delete this API Key?" class="text-red-600 hover:text-red-900">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No API keys created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
