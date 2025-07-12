<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Amazing Sultra Run</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar-mobile.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-blue-900 text-white fixed h-full overflow-y-auto z-50 md:translate-x-0 sidebar-mobile">
            <div class="p-4">
                <h1 class="text-xl font-bold">ASR Admin</h1>
                <p class="text-sm text-blue-200">Amazing Sultra Run</p>
            </div>
            <nav class="mt-8 pb-24">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.ticket-types.index') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.ticket-types.*') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-ticket-alt mr-3"></i>
                    Ticket Types
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.settings') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-cogs mr-3"></i>
                    Settings
                </a>
                <a href="{{ route('admin.unpaid-registrations.index') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.unpaid-registrations.*') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    Unpaid Registrations
                </a>
                <a href="{{ route('admin.whatsapp-queue.index') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.whatsapp-queue.*') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fab fa-whatsapp mr-3"></i>
                    WhatsApp Queue
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.users') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.recent-registrations') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.recent-registrations') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-clock mr-3"></i>
                    Recent Registrations
                </a>
                <a href="{{ route('admin.whatsapp-verification') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.whatsapp-verification') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-phone mr-3"></i>
                    WhatsApp Verification
                </a>
                <a href="{{ route('admin.payment-confirmation') }}" class="flex items-center px-4 py-2 text-blue-200 hover:bg-blue-800 hover:text-white {{ request()->routeIs('admin.payment-confirmation') ? 'bg-blue-800 text-white' : '' }}">
                    <i class="fas fa-credit-card mr-3"></i>
                    Payment Confirmation
                </a>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-800 bg-blue-900">
                <div class="flex items-center">
                    <i class="fas fa-user-circle text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-200">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-blue-200 hover:text-white">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden md:ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </header>

            <!-- Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        mobileOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            mobileOverlay.classList.add('hidden');
        });

        // Close sidebar when clicking nav links on mobile
        const navLinks = sidebar.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('active');
                    mobileOverlay.classList.add('hidden');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
