<div class="min-h-screen w-full flex flex-col md:flex-row">
    {{-- Left Side: Blue Branding Section --}}
    <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center p-8 relative">
        {{-- App Name in Top Left --}}
        <div class="absolute top-8 left-8">
            <h2 class="text-2xl font-bold text-white">{{ config('app.name', 'Medical System') }}</h2>
        </div>

        {{-- Centered Logo --}}
        <div class="flex items-center justify-center">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" 
                     alt="{{ config('app.name', 'Medical System') }}" 
                     class="h-48 w-48 object-contain drop-shadow-2xl">
            @elseif(file_exists(public_path('images/logo.jpg')))
                <img src="{{ asset('images/logo.jpg') }}" 
                     alt="{{ config('app.name', 'Medical System') }}" 
                     class="h-48 w-48 object-contain drop-shadow-2xl">
            @elseif(file_exists(public_path('images/logo.svg')))
                <img src="{{ asset('images/logo.svg') }}" 
                     alt="{{ config('app.name', 'Medical System') }}" 
                     class="h-48 w-48 object-contain drop-shadow-2xl">
            @else
                {{-- Placeholder Logo --}}
                <div class="h-48 w-48 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="h-24 w-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Side: Form Section --}}
    <div class="w-full md:w-1/2 bg-gray-50 flex items-center justify-center p-6 md:p-12">
        {{-- Form Card --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 md:p-10">
            {{-- Title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h1>
                <p class="text-gray-600 text-sm">It's free and easy</p>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="register" class="space-y-5">
                {{-- Name Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your name</label>
                    <input type="text"
                           class="w-full px-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                           wire:model="name"
                           placeholder="Enter your full name"
                           required>
                </div>

                {{-- Email/Phone Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail or phone number</label>
                    <input type="text"
                           class="w-full px-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                           wire:model="email"
                           placeholder="Enter your email or phone"
                           required>
                </div>

                {{-- Password Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password"
                           class="w-full px-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                           wire:model="password"
                           placeholder="Enter your password"
                           required>
                    <p class="text-xs text-gray-500 mt-2">Must be 8 characters at least</p>
                </div>

                {{-- Terms Checkbox --}}
                <div class="flex items-start gap-3">
                    <input type="checkbox" 
                           class="mt-1 checkbox checkbox-primary checkbox-sm" 
                           wire:model="terms"
                           id="terms"
                           required>
                    <label for="terms" class="text-sm text-gray-600 cursor-pointer leading-relaxed">
                        By creating an account means you agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>, and our <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                    </label>
                </div>

                {{-- Register Button --}}
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 mt-6">
                    Register
                </button>
            </form>

            {{-- Social Login Separator --}}
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">or do it via other accounts</span>
                    </div>
                </div>

                {{-- Social Login Buttons --}}
                <div class="flex items-center justify-center gap-4 mt-6">
                    {{-- Google --}}
                    <button type="button" 
                            class="flex items-center justify-center w-12 h-12 rounded-lg border-2 border-gray-200 hover:border-gray-300 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </button>

                    {{-- Apple --}}
                    <button type="button" 
                            class="flex items-center justify-center w-12 h-12 rounded-lg border-2 border-gray-200 hover:border-gray-300 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C1.79 15.25 4.6 8.87 8.38 8.84c1.17.07 2.15.84 3.08.8 1.01-.05 1.78-.67 2.8-.61 1.18.05 2.14.69 2.81 1.8-2.48 1.4-2.12 4.24.9 5.18-.5 1.48-.38 2.28.32 3.27zM12.03 7.89c-.15-2.04 1.66-3.8 3.74-4.04.46 2.92-2.39 5.05-3.74 4.04z"/>
                        </svg>
                    </button>

                    {{-- Facebook --}}
                    <button type="button" 
                            class="flex items-center justify-center w-12 h-12 rounded-lg border-2 border-gray-200 hover:border-gray-300 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Login Link --}}
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Login</a>
                </p>
            </div>
        </div>
    </div>

    {{-- Floating Chat Bubble (Bottom Right) --}}
    <div class="fixed bottom-6 right-6 z-50">
        <button type="button" 
                class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>
    </div>
</div>
