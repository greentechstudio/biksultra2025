@extends('layouts.admin')

@section('title', 'Recent Registrations')

@section('content')
@php
use Illuminate\Support\Facades\Route;
@endphp
<div class="px-4 py-6 sm:px-0">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Registrations</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Total {{ number_format($totalCount ?? $users->total() ?? 0) }} registrations found
                        @if(request()->hasAny(['payment_status', 'whatsapp_verified', 'race_category', 'date_from', 'date_to', 'search']))
                            (filtered)
                        @endif
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button type="button" 
                            onclick="toggleFilters()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>
                        Filters
                        @if(request()->hasAny(['payment_status', 'whatsapp_verified', 'race_category', 'date_from', 'date_to', 'search']))
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Active
                            </span>
                        @endif
                    </button>
                    
                    @php
                    $exportUrl = '';
                    try {
                        $exportUrl = route('admin.recent-registrations.export', request()->query());
                    } catch (\Exception $e) {
                        $exportUrl = url('/admin/recent-registrations/export?' . http_build_query(request()->query()));
                    }
                    @endphp
                    
                    <a href="{{ $exportUrl }}"
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-download mr-2"></i>
                        Export CSV
                    </a>
                    
                    @if(request()->hasAny(['payment_status', 'whatsapp_verified', 'race_category', 'date_from', 'date_to', 'search']))
                    <a href="{{ route('admin.recent-registrations') }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-times mr-2"></i>
                        Clear Filters
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filterPanel" class="border-t border-gray-200 bg-gray-50 px-4 py-4 {{ request()->hasAny(['payment_status', 'whatsapp_verified', 'race_category', 'date_from', 'date_to', 'search']) ? '' : 'hidden' }}">
            <form method="GET" action="{{ route('admin.recent-registrations') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Name, email, or WhatsApp"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" 
                                id="payment_status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <!-- Race Category -->
                    <div>
                        <label for="race_category" class="block text-sm font-medium text-gray-700">Race Category</label>
                        <select name="race_category" 
                                id="race_category"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Categories</option>
                            @foreach($raceCategories ?? [] as $category)
                            <option value="{{ $category }}" {{ request('race_category') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jersey Size -->
                    <div>
                        <label for="jersey_size" class="block text-sm font-medium text-gray-700">Jersey Size</label>
                        <select name="jersey_size" 
                                id="jersey_size"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Sizes</option>
                            @foreach($jerseySizes ?? [] as $size)
                            <option value="{{ $size }}" {{ request('jersey_size') === $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ticket Type -->
                    <div>
                        <label for="ticket_type" class="block text-sm font-medium text-gray-700">Ticket Type</label>
                        <select name="ticket_type" 
                                id="ticket_type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Types</option>
                            @foreach($ticketTypes ?? [] as $type)
                            <option value="{{ $type }}" {{ request('ticket_type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- WhatsApp Verification -->
                    <div>
                        <label for="whatsapp_verified" class="block text-sm font-medium text-gray-700">WhatsApp Status</label>
                        <select name="whatsapp_verified" 
                                id="whatsapp_verified"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="1" {{ request('whatsapp_verified') === '1' ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ request('whatsapp_verified') === '0' ? 'selected' : '' }}>Not Verified</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                        <input type="date" 
                               name="date_from" 
                               id="date_from"
                               value="{{ request('date_from') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                        <input type="date" 
                               name="date_to" 
                               id="date_to"
                               value="{{ request('date_to') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Bar -->
        @if($users->count() > 0)
        <div class="border-t border-gray-200 bg-blue-50 px-4 py-3">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                <div>
                    <div class="text-lg font-semibold text-blue-900">{{ number_format($stats['total'] ?? $users->total() ?? 0) }}</div>
                    <div class="text-xs text-blue-600">Total Found</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-green-900">{{ number_format($stats['paid'] ?? $users->where('payment_confirmed', true)->count()) }}</div>
                    <div class="text-xs text-green-600">Paid</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-yellow-900">{{ number_format($stats['pending'] ?? $users->where('payment_confirmed', false)->count()) }}</div>
                    <div class="text-xs text-yellow-600">Pending Payment</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-blue-900">{{ number_format($stats['whatsapp_verified'] ?? $users->where('whatsapp_verified', true)->count()) }}</div>
                    <div class="text-xs text-blue-600">WhatsApp Verified</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900">{{ number_format($stats['whatsapp_pending'] ?? $users->where('whatsapp_verified', false)->count()) }}</div>
                    <div class="text-xs text-gray-600">WhatsApp Pending</div>
                </div>
            </div>
            
            <!-- Quick Filters -->
            <div class="mt-3 flex flex-wrap justify-center gap-2">
                <a href="{{ route('admin.recent-registrations', array_merge(request()->query(), ['payment_status' => 'pending'])) }}"
                   class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200">
                    <i class="fas fa-clock mr-1"></i>
                    Show Pending
                </a>
                <a href="{{ route('admin.recent-registrations', array_merge(request()->query(), ['payment_status' => 'paid'])) }}"
                   class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                    <i class="fas fa-check mr-1"></i>
                    Show Paid
                </a>
                <a href="{{ route('admin.recent-registrations', array_merge(request()->query(), ['whatsapp_verified' => '0'])) }}"
                   class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    WhatsApp Unverified
                </a>
            </div>
        </div>
        @endif
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($users as $user)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full 
                                        @if($user->payment_confirmed) bg-green-100 @else bg-yellow-100 @endif">
                                        <i class="fas fa-user 
                                            @if($user->payment_confirmed) text-green-600 @else text-yellow-600 @endif"></i>
                                    </span>
                                    @if($user->whatsapp_verified)
                                    <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                        <div class="flex items-center mt-1 space-x-2 text-xs text-gray-400">
                                            <span><i class="fab fa-whatsapp mr-1"></i>{{ $user->whatsapp_number }}</span>
                                            @if($user->gender)
                                            <span>•</span>
                                            <span>{{ $user->gender }}</span>
                                            @endif
                                            @if($user->birth_date)
                                            <span>•</span>
                                            <span>{{ \Carbon\Carbon::parse($user->birth_date)->age }} years</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Race Category & Registration Info -->
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-medium text-gray-900">{{ $user->race_category }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $user->created_at->format('H:i') }}</div>
                                
                                <div class="flex items-center justify-end space-x-2 mt-1">
                                    @if($user->jersey_size)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-tshirt mr-1"></i>{{ $user->jersey_size }}
                                    </span>
                                    @endif
                                    
                                    @if($user->ticketType)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                                        @if(str_contains(strtolower($user->ticketType->name), 'early'))
                                            bg-green-100 text-green-800
                                        @else
                                            bg-blue-100 text-blue-800
                                        @endif">
                                        {{ $user->ticketType->name }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Status Badges -->
                            <div class="flex flex-col items-end space-y-1">
                                <!-- Payment Status -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->payment_confirmed) 
                                        bg-green-100 text-green-800 
                                    @else 
                                        bg-yellow-100 text-yellow-800 
                                    @endif">
                                    @if($user->payment_confirmed)
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Paid
                                    @else
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    @endif
                                </span>
                                
                                <!-- WhatsApp Status -->
                                @if($user->whatsapp_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fab fa-whatsapp mr-1"></i>
                                    Verified
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Not Verified
                                </span>
                                @endif
                                
                                <!-- Registration Duration -->
                                <div class="text-xs text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="flex-shrink-0">
                                <div class="relative inline-block text-left">
                                    <button type="button" 
                                            onclick="toggleActions({{ $user->id }})"
                                            class="bg-white rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span class="sr-only">Open options</span>
                                        <i class="fas fa-ellipsis-v h-4 w-4"></i>
                                    </button>
                                    
                                    <div id="actions-{{ $user->id }}" 
                                         class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                        <div class="py-1">
                                            @if(!$user->whatsapp_verified)
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fab fa-whatsapp mr-2"></i>
                                                Send WhatsApp Verification
                                            </a>
                                            @endif
                                            
                                            @if(!$user->payment_confirmed)
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-dollar-sign mr-2"></i>
                                                Send Payment Reminder
                                            </a>
                                            @endif
                                            
                                            <a href="mailto:{{ $user->email }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-envelope mr-2"></i>
                                                Send Email
                                            </a>
                                            
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp_number) }}" 
                                               target="_blank"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fab fa-whatsapp mr-2"></i>
                                                WhatsApp Chat
                                            </a>
                                            
                                            <div class="border-t border-gray-100"></div>
                                            
                                            <a href="#" 
                                               onclick="viewUserDetails({{ $user->id }})"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-2"></i>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile view for race category and additional info -->
                    <div class="mt-2 sm:hidden">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-gray-900">{{ $user->race_category }}</span>
                            <span class="text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($user->jersey_size)
                        <div class="text-xs text-purple-600 mt-1">Jersey Size: {{ $user->jersey_size }}</div>
                        @endif
                    </div>
                </li>
                @empty
                <li class="px-4 py-12 sm:px-6 text-center text-gray-500">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium">No registrations found</p>
                    <p class="text-sm">Try adjusting your filter criteria</p>
                </li>
                @endforelse
            </ul>
        </div>
        
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ number_format($users->total()) }} results
                </div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleFilters() {
    const panel = document.getElementById('filterPanel');
    panel.classList.toggle('hidden');
}

function toggleActions(userId) {
    // Close all other action menus
    const allMenus = document.querySelectorAll('[id^="actions-"]');
    allMenus.forEach(menu => {
        if (menu.id !== `actions-${userId}`) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    const menu = document.getElementById(`actions-${userId}`);
    menu.classList.toggle('hidden');
}

function viewUserDetails(userId) {
    // This would typically open a modal or redirect to a detail page
    // For now, we'll just show an alert
    alert(`View details for user ID: ${userId}`);
    // You can implement this to open a modal with full user details
}

// Close action menus when clicking outside
document.addEventListener('click', function(event) {
    const isActionButton = event.target.closest('button[onclick*="toggleActions"]');
    const isActionMenu = event.target.closest('[id^="actions-"]');
    
    if (!isActionButton && !isActionMenu) {
        const allMenus = document.querySelectorAll('[id^="actions-"]');
        allMenus.forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Auto-submit form when filter changes (optional)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filterPanel form');
    const inputs = form.querySelectorAll('select, input[type="date"]');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optional: Auto-submit on change
            // form.submit();
        });
    });

    // Handle Enter key in search input
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                form.submit();
            }
        });
    }
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+F to focus search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape to close action menus
        if (e.key === 'Escape') {
            const allMenus = document.querySelectorAll('[id^="actions-"]');
            allMenus.forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
});
</script>
@endsection
