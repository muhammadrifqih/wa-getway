<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 border-b border-gray-200">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Admin Overview
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Manage your SaaS users, devices, and monitor system performance.
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                <span class="h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                System Healthy
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg relative group">
            <div class="absolute -right-4 -top-4 opacity-20 group-hover:opacity-30 transition-opacity">
                <svg class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="p-6 relative z-10">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-md bg-white/20 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-blue-100 truncate">Total Users</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalUsers) }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg relative group">
            <div class="absolute -right-4 -top-4 opacity-20 group-hover:opacity-30 transition-opacity">
                <svg class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div class="p-6 relative z-10">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-md bg-white/20 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-teal-100 truncate">Connected Devices</p>
                    <div class="flex items-baseline mt-1 gap-2">
                        <p class="text-3xl font-bold text-white">{{ number_format($connectedDevices) }}</p>
                        <p class="text-sm font-medium text-teal-200">/ {{ number_format($totalDevices) }} total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg relative group">
            <div class="absolute -right-4 -top-4 opacity-20 group-hover:opacity-30 transition-opacity">
                <svg class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <div class="p-6 relative z-10">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-md bg-white/20 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-indigo-100 truncate">Total Sent Messages</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($sentMessages) }}</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg relative group">
            <div class="absolute -right-4 -top-4 opacity-20 group-hover:opacity-30 transition-opacity">
                <svg class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="p-6 relative z-10">
                <div class="flex items-center">
                    <div class="flex-shrink-0 rounded-md bg-white/20 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-orange-100 truncate">Queue Usage (All Time)</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalMessages) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Users List -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900">Recent Registrations</h3>
                <button class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View All Users &rarr;</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($recentUsers as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">Admin</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Client</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
                                <p class="mt-1 text-sm text-gray-500">No users have registered yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users') }}" class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors text-left group">
                        <div class="flex items-center text-sm font-medium text-gray-700 group-hover:text-indigo-700">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Manage Users
                        </div>
                    </a>
                    <a href="{{ route('admin.plans') }}" class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors text-left group">
                        <div class="flex items-center text-sm font-medium text-gray-700 group-hover:text-indigo-700">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Manage Subscriptions
                        </div>
                    </a>
                    <button class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors text-left group">
                        <div class="flex items-center text-sm font-medium text-gray-700 group-hover:text-indigo-700">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            System Logs
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Server Status -->
            <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-xl shadow-sm border border-gray-800 p-6 text-white">
                <h3 class="text-lg font-semibold mb-4 text-gray-100">Service Status</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                            <span class="text-sm text-gray-300">Node.js Engine</span>
                        </div>
                        <span class="text-xs font-mono bg-gray-700 px-2 py-1 rounded">Online</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                            <span class="text-sm text-gray-300">Redis Queue</span>
                        </div>
                        <span class="text-xs font-mono bg-gray-700 px-2 py-1 rounded">Online</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                            <span class="text-sm text-gray-300">MySQL Database</span>
                        </div>
                        <span class="text-xs font-mono bg-gray-700 px-2 py-1 rounded">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
