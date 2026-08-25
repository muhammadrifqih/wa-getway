<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WAGateway - SaaS WhatsApp API Gateway</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#10b981', secondary: '#047857' }
                }
            }
        }
    </script>
</head>
<body class="antialiased text-gray-800 bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <svg class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                    <span class="font-bold text-xl tracking-tight text-gray-900">WAGateway</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="text-gray-500 hover:text-gray-900 font-medium">Features</a>
                    <a href="#pricing" class="text-gray-500 hover:text-gray-900 font-medium">Pricing</a>
                    <a href="#docs" class="text-gray-500 hover:text-gray-900 font-medium">API Docs</a>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-primary hover:text-secondary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-secondary">Sign up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">The Most Reliable</span>
                <span class="block text-primary">WhatsApp API Gateway</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Send messages, OTPs, and receive real-time webhooks instantly. Connect your application to WhatsApp in minutes using our simple REST API. No Baileys setup required.
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="{{ route('register') }}" class="rounded-md bg-primary px-8 py-3 text-base font-medium text-white hover:bg-secondary md:py-4 md:px-10">Get Started for Free</a>
                <a href="#docs" class="rounded-md bg-gray-100 px-8 py-3 text-base font-medium text-gray-700 hover:bg-gray-200 md:py-4 md:px-10">Read the Docs</a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div id="features" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Everything you need</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">All-in-one Messaging Platform</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Multi Device -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Multi Device Ready</h3>
                    <p class="mt-2 text-gray-500">Scan QR directly from our dashboard. Connect multiple WhatsApp numbers and switch between them dynamically via API.</p>
                </div>
                <!-- Webhooks -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Real-time Webhooks</h3>
                    <p class="mt-2 text-gray-500">Receive incoming WhatsApp messages directly to your server instantly. Perfect for building custom chatbots.</p>
                </div>
                <!-- Smart Queue -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Smart Queueing</h3>
                    <p class="mt-2 text-gray-500">Don't worry about rate limits. Blast thousands of messages safely with our built-in delayed queue processor.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- API Docs -->
    <div id="docs" class="py-16 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold tracking-tight">API Documentation</h2>
                <p class="mt-4 text-gray-400">Integrate WhatsApp in your language of choice with standard REST requests.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Endpoint 1 -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-green-500 text-green-900 font-bold text-sm rounded">POST</span>
                        <code class="text-gray-300">/api/v1/messages</code>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Send a text message to a number.</p>
                    <div class="bg-black p-4 rounded text-sm text-green-400 font-mono overflow-x-auto">
<pre>curl -X POST http://localhost:8000/api/v1/messages \
  -H "Authorization: Bearer wa_live_your_api_key" \
  -H "Content-Type: application/json" \
  -d '{
    "target": "628123456789",
    "message": "Hello from WAGateway!"
  }'</pre>
                    </div>
                </div>

                <!-- Endpoint 2 -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-green-500 text-green-900 font-bold text-sm rounded">POST</span>
                        <code class="text-gray-300">/api/v1/otp/send</code>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Generate and send a 6-digit OTP code.</p>
                    <div class="bg-black p-4 rounded text-sm text-green-400 font-mono overflow-x-auto">
<pre>curl -X POST http://localhost:8000/api/v1/otp/send \
  -H "Authorization: Bearer wa_live_your_api_key" \
  -H "Content-Type: application/json" \
  -d '{
    "target": "628123456789",
    "purpose": "login"
  }'</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; 2026 WAGateway. Built for performance and reliability.
        </div>
    </footer>
</body>
</html>
