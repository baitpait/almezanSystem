<!DOCTYPE html>
<html lang="en" data-theme="medical" data-csrf="{{ csrf_token() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'AlmezanSystem') }} - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Google Fonts - Cairo Arabic Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 h-screen font-cairo overflow-hidden">
    @auth
    {{-- Sidebar & Main Content --}}
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        {{-- Sidebar (must be first for left positioning) --}}
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay lg:hidden"></label>
            <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-700 shadow-2xl h-screen flex flex-col">
                {{-- Mobile Header --}}
                <div class="p-4 border-b border-blue-500/30 lg:hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if(file_exists(public_path('images/logo.png')))
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center p-1.5">
                                    <img src="{{ asset('images/logo.png') }}" alt="مركز الغد لجراحة العيون والليزك" class="w-full h-full object-contain">
                                </div>
                            @endif
                            <h2 class="text-sm font-bold text-white">مركز الغد</h2>
                        </div>
                        <label for="drawer-toggle" class="btn btn-ghost btn-sm btn-circle text-white hover:bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </label>
                    </div>
                </div>
                
                {{-- Logo in Desktop Sidebar --}}
                <div class="p-5 border-b border-blue-500/30 hidden lg:block">
                    <div class="flex items-center gap-3">
                        @if(file_exists(public_path('images/logo.png')))
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2 shadow-lg">
                                <img src="{{ asset('images/logo.png') }}" alt="مركز الغد لجراحة العيون والليزك" class="w-full h-full object-contain">
                            </div>
                        @endif
                        <div>
                            <h2 class="text-base font-bold text-white">مركز الغد</h2>
                            <p class="text-xs text-blue-100">لجراحة العيون والليزك</p>
                        </div>
                    </div>
                </div>

                {{-- User Profile Card --}}
                <div class="p-6 border-b border-blue-500/30">
                    <div class="flex flex-col items-center text-center">
                        @php
                            $user = auth()->user();
                            $photoUrl = null;
                            if ($user->photo) {
                                $storagePath = storage_path('app/public/' . $user->photo);
                                $publicPath = public_path('storage/' . $user->photo);
                                $directPath = public_path($user->photo);
                                
                                if (file_exists($storagePath) || file_exists($publicPath)) {
                                    $photoUrl = asset('storage/' . $user->photo);
                                } elseif (file_exists($directPath)) {
                                    $photoUrl = asset($user->photo);
                                }
                            }
                        @endphp
                        <div class="mb-4">
                            @if($photoUrl)
                                <div class="w-24 h-24 rounded-full ring-4 ring-white/20 shadow-xl overflow-hidden">
                                    <img src="{{ $photoUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-24 h-24 bg-white/20 rounded-full ring-4 ring-white/20 shadow-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">{{ $user->name }}</h3>
                        <p class="text-sm text-blue-100">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
                
                {{-- Navigation Menu --}}
                <ul class="menu p-4 gap-1 flex-1 overflow-y-auto">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    @can('view.patients')
                    <li>
                        <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('patients.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Patients</span>
                        </a>
                    </li>
                    @endcan
                    
                    @can('view.appointments')
                    <li>
                        <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('appointments.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Appointments</span>
                        </a>
                    </li>
                    @endcan
                    
                    @can('view.invoices')
                    <li>
                        <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('invoices.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Invoices</span>
                        </a>
                    </li>
                    @endcan

                    @can('view.services')
                    <li>
                        <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('services.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Services</span>
                        </a>
                    </li>
                    @endcan

                    @can('view.assessment')
                    <li>
                        <a href="{{ route('operations.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('operations.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Assessment</span>
                        </a>
                    </li>
                    @endcan
                    
                    @can('view.operations')
                    <li>
                        <a href="{{ route('scheduled-operations.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('scheduled-operations.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Operations</span>
                        </a>
                    </li>
                    @endcan

                    @canany(['view.users', 'view.doctors', 'view.branches'])
                    <li class="pt-2 mt-2 border-t border-blue-500/30">
                        <span class="text-blue-200 text-xs font-semibold uppercase tracking-wider px-4 py-1.5">Administration</span>
                    </li>
                    @can('view.users')
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('admin.users.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Users</span>
                        </a>
                    </li>
                    @endcan
                    @can('view.doctors')
                    <li>
                        <a href="{{ route('admin.doctors.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('admin.doctors.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 4a4 4 0 118 0v1a4 4 0 11-8 0V4zM6 20a6 6 0 1112 0v1H6v-1z" />
                            </svg>
                            <span>Doctors</span>
                        </a>
                    </li>
                    @endcan
                    @can('view.branches')
                    <li>
                        <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('admin.branches.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Branches</span>
                        </a>
                    </li>
                    @endcan
                    @can('view.users')
                    <li>
                        <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('admin.roles.*') ? 'bg-blue-500 text-white shadow-lg font-semibold' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Roles</span>
                        </a>
                    </li>
                    @endcan
                    @endcanany
                    
                    {{-- Profile Link --}}
                    <li class="mt-auto pt-1">
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white transition-all {{ request()->routeIs('profile') ? 'bg-blue-500 text-white shadow-lg' : 'hover:bg-blue-500/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile Settings</span>
                        </a>
                    </li>
                    
                    {{-- Logout --}}
                    <li class="pt-1">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white hover:bg-red-500/30 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </aside>
        </div>
        
        {{-- Main Content --}}
        <div class="drawer-content flex flex-col">
            {{-- Mobile Menu Button --}}
            <div class="lg:hidden p-3 border-b border-base-300 bg-base-100">
                <label for="drawer-toggle" class="btn btn-ghost btn-sm drawer-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
            </div>
            <main class="flex-1 p-3 md:p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @else
    <main class="min-h-screen flex items-center justify-center p-4 bg-base-200">
        {{ $slot }}
    </main>
    @endauth

    {{-- Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    @livewireScripts
    @vite('resources/js/app.js')
    
    <script>
        // Handle 419 Page Expired errors
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                        window.location.reload();
                    }
                });
            });
        });

        // Store original parents for dropdowns
        const dropdownOriginalParents = new Map();
        
        // Simple Dropdown Functions - Works reliably
        window.toggleSimpleDropdown = function(patientId, event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            
            const menu = document.getElementById('dropdown-menu-' + patientId);
            if (!menu) {
                console.error('Menu not found for patient:', patientId);
                return;
            }
            
            const button = event.target.closest('button[data-dropdown-trigger="' + patientId + '"]');
            if (!button) {
                console.error('Button not found');
                return;
            }
            
            const container = button.closest('[data-dropdown-container="' + patientId + '"]');
            if (!container) {
                console.error('Container not found');
                return;
            }
            
            // Check if menu is currently visible
            const isVisible = menu.style.display === 'block';
            
            // Close all dropdowns first
            document.querySelectorAll('.simple-dropdown-menu').forEach(m => {
                if (m.id !== 'dropdown-menu-' + patientId) {
                    closeSimpleDropdown(parseInt(m.dataset.dropdownMenu));
                }
            });
            
            if (isVisible) {
                // Close this dropdown
                closeSimpleDropdown(patientId);
            } else {
                // Open this dropdown
                const rect = button.getBoundingClientRect();
                
                // Store original parent if not stored
                if (!dropdownOriginalParents.has(patientId)) {
                    dropdownOriginalParents.set(patientId, {
                        parent: container,
                        nextSibling: menu.nextSibling
                    });
                }
                
                // Move menu to body
                if (menu.parentElement !== document.body) {
                    document.body.appendChild(menu);
                }
                
                // Calculate position - ensure it doesn't go off screen
                const menuWidth = 144; // 9rem = 144px
                const menuHeight = 200; // Approximate height
                
                // Calculate position relative to button
                let top = rect.bottom + window.scrollY + 4;
                let left = rect.left + window.scrollX;
                
                // For sticky right columns, align menu to the right edge of button
                // Check if button is in a sticky right column
                const isStickyRight = button.closest('td.sticky.right-0') || button.closest('[class*="sticky"][class*="right"]');
                
                if (isStickyRight) {
                    // Align to right edge of button
                    left = rect.right + window.scrollX - menuWidth;
                } else {
                    // Normal positioning - align to left edge of button
                    left = rect.left + window.scrollX;
                }
                
                // If menu would go off the right edge, align to left of button
                if (left + menuWidth > window.innerWidth + window.scrollX) {
                    left = rect.left + window.scrollX - menuWidth;
                }
                
                // If menu would go off the left edge, align to right of button
                if (left < window.scrollX) {
                    left = rect.right + window.scrollX;
                }
                
                // If menu would go off the bottom, show above button
                if (top + menuHeight > window.innerHeight + window.scrollY) {
                    top = rect.top + window.scrollY - menuHeight - 4;
                }
                
                // Ensure minimum distance from edges
                if (top < window.scrollY + 10) {
                    top = window.scrollY + 10;
                }
                if (left < window.scrollX + 10) {
                    left = window.scrollX + 10;
                }
                
                // Show menu
                menu.style.display = 'block';
                menu.style.position = 'fixed';
                menu.style.top = top + 'px';
                menu.style.left = left + 'px';
                menu.style.zIndex = '999999';
            }
        };
        
        window.closeSimpleDropdown = function(patientId) {
            const menu = document.getElementById('dropdown-menu-' + patientId);
            if (!menu) return;
            
            // Hide menu
            menu.style.display = 'none';
            
            // Return menu to original parent if stored
            if (dropdownOriginalParents.has(patientId)) {
                const original = dropdownOriginalParents.get(patientId);
                if (menu.parentElement === document.body && original.parent) {
                    if (original.nextSibling) {
                        original.parent.insertBefore(menu, original.nextSibling);
                    } else {
                        original.parent.appendChild(menu);
                    }
                }
            }
        };
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const clickedButton = e.target.closest('button[data-dropdown-trigger]');
            const clickedMenu = e.target.closest('.simple-dropdown-menu');
            
            if (!clickedButton && !clickedMenu) {
                // Close all dropdowns
                document.querySelectorAll('.simple-dropdown-menu').forEach(menu => {
                    const patientId = parseInt(menu.dataset.dropdownMenu);
                    if (patientId) {
                        closeSimpleDropdown(patientId);
                    }
                });
            }
        }, true);
        
        // Close dropdowns on scroll
        window.addEventListener('scroll', function() {
            document.querySelectorAll('.simple-dropdown-menu').forEach(menu => {
                const patientId = parseInt(menu.dataset.dropdownMenu);
                if (patientId) {
                    closeSimpleDropdown(patientId);
                }
            });
        }, true);
        
        // Re-initialize after Livewire updates
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                // Clear stored parents and close all dropdowns
                dropdownOriginalParents.clear();
                document.querySelectorAll('.simple-dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
                
                // Ensure drawer is visible after Livewire updates
                if (window.innerWidth >= 1024) {
                    const drawer = document.querySelector('.drawer');
                    const drawerSide = document.querySelector('.drawer-side');
                    if (drawer && drawerSide) {
                        drawer.classList.add('lg:drawer-open');
                        drawer.classList.remove('drawer-end');
                        drawer.style.flexDirection = 'row';
                        drawerSide.style.display = 'block';
                        drawerSide.style.visibility = 'visible';
                        drawerSide.style.opacity = '1';
                        drawerSide.style.order = '-1';
                        drawerSide.style.left = '0';
                        drawerSide.style.right = 'auto';
                    }
                }
            });
        });
        
        // Ensure drawer is visible on page load and after navigation
        function ensureDrawerVisible() {
            if (window.innerWidth >= 1024) {
                const drawer = document.querySelector('.drawer');
                const drawerSide = document.querySelector('.drawer-side');
                if (drawer && drawerSide) {
                    drawer.classList.add('lg:drawer-open');
                    drawer.classList.remove('drawer-end');
                    drawer.style.flexDirection = 'row';
                    drawerSide.style.display = 'block';
                    drawerSide.style.visibility = 'visible';
                    drawerSide.style.opacity = '1';
                    drawerSide.style.order = '-1';
                    drawerSide.style.left = '0';
                    drawerSide.style.right = 'auto';
                }
            }
        }
        
        // Run on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', ensureDrawerVisible);
        } else {
            ensureDrawerVisible();
        }
        
        // Run after Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            setTimeout(ensureDrawerVisible, 100);
        });
    </script>
</body>
</html>
