<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Runer Running Club')</title>
    
    {{-- Base Styles --}}
    @include('partials.styles-complete')
    
    {{-- Additional Styles --}}
    @stack('styles')
</head>
<body>
    {{-- Main Content --}}
    @yield('content')

    {{-- Admin Access Link --}}
    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="admin-access">
                Admin Dashboard
            </a>
        @endif
    @endauth
    
    {{-- JavaScript --}}
    @include('partials.scripts-complete')
    @stack('scripts')
</body>
</html>
