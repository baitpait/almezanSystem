<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-4">
    {{-- Main Container: Landscape Split-Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        {{-- Left Column: Form Section --}}
        <div class="p-8 md:p-12 flex flex-col justify-center bg-white">
            {{-- Login Heading --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-blue-900 mb-2">Login</h1>
                <p class="text-gray-600 text-sm">Welcome to {{ config('app.name', 'مركز الغد لجراحة العيون والليزك') }}</p>
            </div>

            {{-- General Error Message (only show if not Livewire errors) --}}
            @if (session('error') && !$errors->any())
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form wire:submit="login" autocomplete="off" class="space-y-5">
                {{-- Email/Username Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username or Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="email"
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none @error('email') border-red-500 @enderror"
                               wire:model.blur="email"
                               placeholder="Enter your email or username"
                               autocomplete="email"
                               required>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">Forgot?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password"
                               id="password-input"
                               class="w-full pl-10 pr-12 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none @error('password') border-red-500 @enderror"
                               wire:model.blur="password"
                               placeholder="Enter your password"
                               autocomplete="current-password"
                               required>
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                tabindex="-1">
                            <svg id="password-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: block;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="password-eye-off" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" 
                           class="checkbox checkbox-primary checkbox-sm" 
                           wire:model="remember" 
                           id="remember">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer">
                        Remember me
                        <span class="text-xs text-gray-500 block mt-0.5">(Stay logged in for extended period)</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="login"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 mt-6 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Logging in...
                    </span>
                </button>
            </form>

            {{-- Developer Link --}}
            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <a href="https://baitpait.com" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors hover:underline">
                    Powered by BaitPait © {{ date('Y') }}
                </a>
            </div>
        </div>
        
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password-input');
                const eyeIcon = document.getElementById('password-eye');
                const eyeOffIcon = document.getElementById('password-eye-off');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                }
            }
        </script>

        {{-- Right Column: Branding Section with Wave --}}
        <div class="relative bg-gradient-to-br from-blue-500 to-blue-700 hidden md:flex items-center justify-center p-12">
            {{-- Wave Separator SVG --}}
            <div class="absolute top-0 bottom-0 -left-1 w-16 h-full pointer-events-none z-10">
                <svg class="h-full w-full text-white" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 C 40 10 60 40 40 100 L 0 100 Z" />
                </svg>
            </div>

            {{-- Logo Container --}}
            <div class="relative z-20 flex flex-col items-center justify-center text-center">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-40 w-40 object-contain mb-6 drop-shadow-2xl">
                @elseif(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-40 w-40 object-contain mb-6 drop-shadow-2xl">
                @elseif(file_exists(public_path('images/logo.svg')))
                    <img src="{{ asset('images/logo.svg') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-40 w-40 object-contain mb-6 drop-shadow-2xl">
                @else
                    {{-- Fallback if no logo --}}
                    <div class="h-40 w-40 bg-white/20 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-20 w-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <h2 class="text-3xl font-bold text-white mb-2">{{ config('app.name', 'مركز الغد لجراحة العيون والليزك') }}</h2>
                <p class="text-blue-100 text-sm">Professional Medical Management</p>
            </div>
        </div>

        {{-- Mobile: Show Branding at Top --}}
        <div class="md:hidden bg-gradient-to-br from-blue-500 to-blue-700 p-8 flex items-center justify-center">
            <div class="flex flex-col items-center justify-center text-center">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-24 w-24 object-contain mb-4 drop-shadow-lg">
                @elseif(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-24 w-24 object-contain mb-4 drop-shadow-lg">
                @elseif(file_exists(public_path('images/logo.svg')))
                    <img src="{{ asset('images/logo.svg') }}" 
                         alt="{{ config('app.name', 'Medical System') }}" 
                         class="h-24 w-24 object-contain mb-4 drop-shadow-lg">
                @endif
                <h2 class="text-xl font-bold text-white">{{ config('app.name', 'Medical System') }}</h2>
            </div>
        </div>
    </div>
</div>
