<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center mb-8">
        <h2 class="text-xl font-semibold text-gray-800">Welcome Back</h2>
        <p class="text-sm text-gray-500 mt-1">Please enter your details to sign in.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email Address" class="text-gray-700 font-medium" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors" type="email" name="email" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Password" class="text-gray-700 font-medium" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                        Forgot password?
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me for 30 days</label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Sign In
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>Sign up for free</a>
    </div>
</div>
