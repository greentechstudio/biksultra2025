<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">{{ config('app.name') }}</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('profile.show') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ auth()->user()->name }}
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-200" id="chevronIcon"></i>
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200 hidden">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-user mr-3"></i>Lihat Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-user-edit mr-3"></i>Edit Profile
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors focus:outline-none focus:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        @hasSection('header')
        <div class="mb-6">
            <div class="bg-white shadow rounded-lg px-6 py-4">
                @yield('header')
            </div>
        </div>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="px-4 py-6 sm:px-0">
            @yield('content')
        </div>
    </main>

    <script>
        // User dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const chevronIcon = document.getElementById('chevronIcon');
            let isDropdownOpen = false;

            // Toggle dropdown when button is clicked
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            // Prevent dropdown from closing when clicking inside it
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            function toggleDropdown() {
                if (isDropdownOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            }

            function openDropdown() {
                userDropdown.classList.remove('hidden');
                chevronIcon.style.transform = 'rotate(180deg)';
                isDropdownOpen = true;
                
                // Add focus trap for accessibility
                userDropdown.focus();
            }

            function closeDropdown() {
                userDropdown.classList.add('hidden');
                chevronIcon.style.transform = 'rotate(0deg)';
                isDropdownOpen = false;
            }

            // Add hover delay for better UX
            let hoverTimeout;
            
            userMenuButton.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                if (!isDropdownOpen) {
                    hoverTimeout = setTimeout(openDropdown, 200); // 200ms delay
                }
            });

            userMenuButton.addEventListener('mouseleave', function() {
                clearTimeout(hoverTimeout);
            });

            // Keep dropdown open when hovering over it
            userDropdown.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
            });

            userDropdown.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(closeDropdown, 300); // 300ms delay before closing
            });
        });
    </script>
</body>
</html>
