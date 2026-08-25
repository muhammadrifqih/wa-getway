<div class="space-y-6">
    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <svg class="h-48 w-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}! 👋</h2>
            <p class="mt-2 text-indigo-100 text-lg max-w-2xl">Here is what's happening with your WhatsApp Gateway today. You can monitor your active devices, quota usage, and recent message logs below.</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Device Stat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-4 rounded-full bg-green-100 text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Connected Devices</p>
                <div class="flex items-end gap-2">
                    <p class="text-2xl font-bold text-gray-900">{{ $connectedDeviceCount }}</p>
                    <p class="text-sm text-gray-400 mb-1">/ {{ $deviceCount }} Total</p>
                </div>
            </div>
        </div>

        <!-- Quota Stat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-4 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <div class="w-full">
                <p class="text-sm font-medium text-gray-500">Monthly Quota Used</p>
                <div class="flex justify-between items-end mb-1">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($messageCount) }}</p>
                    <p class="text-xs text-gray-400">{{ $subscription && $subscription->plan->max_messages > 0 ? number_format($subscription->plan->max_messages) : 'Unlimited' }}</p>
                </div>
                @if($subscription && $subscription->plan->max_messages > 0)
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        @php $percent = min(100, ($messageCount / $subscription->plan->max_messages) * 100); @endphp
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Webhook Stat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-4 rounded-full {{ $webhookActive ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-400' }} mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Incoming Webhook</p>
                <p class="text-xl font-bold {{ $webhookActive ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $webhookActive ? 'Active' : 'Disabled' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Middle Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Outgoing Messages -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900">Recent API Messages</h3>
                <a href="{{ route('devices') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Send New &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($recentMessages as $msg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                +{{ $msg->target }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($msg->status === 'sent')
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Sent</span>
                                @elseif($msg->status === 'failed')
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Failed</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $msg->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No messages sent</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by generating an API Key and sending your first message.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Helpful Links -->
        <div class="space-y-6">
            <!-- Active Plan -->
            <div class="bg-gray-900 rounded-xl shadow-sm border border-gray-800 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-2 -mr-2 opacity-10 text-yellow-400">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold mb-1 text-gray-100 relative z-10">Your Subscription</h3>
                <p class="text-3xl font-bold text-yellow-400 mb-4 relative z-10">
                    {{ $subscription ? $subscription->plan->name : 'Free Trial' }}
                </p>
                <a href="{{ route('billing') }}" class="inline-flex w-full justify-center items-center px-4 py-2 bg-white text-gray-900 text-sm font-semibold rounded hover:bg-gray-100 transition-colors relative z-10">
                    Upgrade Plan
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Setup</h3>
                <div class="space-y-3">
                    <a href="{{ route('devices') }}" class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Add WhatsApp Device</span>
                    </a>
                    <a href="{{ route('api-keys') }}" class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Generate API Key</span>
                    </a>
                    <a href="{{ route('webhooks') }}" class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Configure Webhook</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
