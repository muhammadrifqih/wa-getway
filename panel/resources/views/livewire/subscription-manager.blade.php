<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Subscription & Quota
        </h2>
        <p class="text-sm text-gray-500 mt-1">Manage your billing and view your current usage.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <!-- Current Usage Overview -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Usage</h3>
        @if($subscription)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Active Plan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $subscription->plan->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">Expires on {{ $subscription->expired_at ? $subscription->expired_at->format('M d, Y') : 'Never' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Messages Sent (This Month)</p>
                    <div class="flex items-end gap-2">
                        <p class="text-2xl font-bold {{ ($subscription->plan->max_messages > 0 && $messageCount >= $subscription->plan->max_messages) ? 'text-red-600' : 'text-indigo-600' }}">
                            {{ number_format($messageCount) }}
                        </p>
                        <p class="text-sm text-gray-500 mb-1">
                            / {{ $subscription->plan->max_messages > 0 ? number_format($subscription->plan->max_messages) : 'Unlimited' }}
                        </p>
                    </div>
                    @if($subscription->plan->max_messages > 0)
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            @php $percent = min(100, ($messageCount / $subscription->plan->max_messages) * 100); @endphp
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative">
                <strong>No Active Subscription!</strong> You cannot send messages until you select a plan.
            </div>
        @endif
    </div>

    <!-- Available Plans -->
    <h3 class="text-lg font-medium text-gray-900 mb-4">Available Plans</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-lg shadow-sm border {{ $subscription && $subscription->plan_id == $plan->id ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200' }} p-6 flex flex-col">
                <div class="mb-4">
                    <h4 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h4>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">
                        Rp {{ number_format($plan->price, 0, ',', '.') }}<span class="text-base font-medium text-gray-500">/mo</span>
                    </p>
                </div>
                
                <ul class="mt-4 space-y-3 flex-1">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="ml-2 text-sm text-gray-600">{{ $plan->max_devices > 0 ? $plan->max_devices : 'Unlimited' }} WhatsApp Devices</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="ml-2 text-sm text-gray-600">{{ $plan->max_messages > 0 ? number_format($plan->max_messages) : 'Unlimited' }} Messages/mo</span>
                    </li>
                </ul>

                <div class="mt-6">
                    @if($subscription && $subscription->plan_id == $plan->id)
                        <button disabled class="w-full block text-center rounded-md border border-transparent bg-gray-100 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                            Current Plan
                        </button>
                    @else
                        <button wire:click="subscribe({{ $plan->id }})" wire:confirm="Upgrade to {{ $plan->name }}?" class="w-full block text-center rounded-md border border-transparent bg-indigo-600 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                            Select Plan
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
