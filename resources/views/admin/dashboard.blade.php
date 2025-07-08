@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Registrations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_registrations']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Paid Registrations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['paid_registrations']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Registrations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['pending_registrations']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-percentage text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['conversion_rate'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Potential Analysis -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue Potential Analysis</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Breakdown of potential revenue from all registrations</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                <!-- Total Potential Revenue -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Total Potential</h4>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($stats['total_potential_revenue'], 0, ',', '.') }}</p>
                            <p class="text-sm mt-1 opacity-90">From {{ number_format($stats['total_registrations']) }} registrations</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-4xl opacity-80"></i>
                        </div>
                    </div>
                </div>

                <!-- Paid Revenue -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Paid Revenue</h4>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                            <p class="text-sm mt-1 opacity-90">{{ number_format($stats['paid_registrations']) }} payments confirmed</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-4xl opacity-80"></i>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Potential -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Unpaid Potential</h4>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($stats['unpaid_potential_revenue'], 0, ',', '.') }}</p>
                            <p class="text-sm mt-1 opacity-90">{{ number_format($stats['pending_registrations']) }} pending payments</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-4xl opacity-80"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Progress Bar -->
            <div class="px-6 pb-6">
                <div class="bg-gray-200 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full transition-all duration-300" style="width: {{ $stats['total_potential_revenue'] > 0 ? round(($stats['total_revenue'] / $stats['total_potential_revenue']) * 100, 1) : 0 }}%"></div>
                </div>
                <div class="flex justify-between mt-2 text-sm text-gray-600">
                    <span>Revenue Achievement: {{ $stats['total_potential_revenue'] > 0 ? round(($stats['total_revenue'] / $stats['total_potential_revenue']) * 100, 1) : 0 }}%</span>
                    <span>Remaining: {{ $stats['total_potential_revenue'] > 0 ? round((($stats['total_potential_revenue'] - $stats['total_revenue']) / $stats['total_potential_revenue']) * 100, 1) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Registrations -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Registrations</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest 5 registrations</p>
                </div>
                @if($stats['total_recent_registrations'] > 5)
                <div>
                    <a href="{{ route('admin.recent-registrations') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View All ({{ number_format($stats['total_recent_registrations']) }})
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse($stats['recent_registrations'] as $registration)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->email }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($registration->status === 'paid') bg-green-100 text-green-800 
                                    @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($registration->status) }}
                                </span>
                                <div class="ml-4 text-sm text-gray-500">
                                    {{ $registration->race_category }}
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                        No recent registrations
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Category Statistics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Category Statistics</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Registration count by race category</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    @foreach($stats['category_stats'] as $category)
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                                    <i class="fas fa-running text-indigo-600"></i>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                <div class="text-sm text-gray-500">Rp {{ number_format($category->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">{{ $category->users_count }}</div>
                            <div class="text-sm text-gray-500">participants</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Status</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current system status overview</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['active_ticket_types'] }}</div>
                        <div class="text-sm text-gray-500">Active Ticket Types</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['whatsapp_queue_count'] }}</div>
                        <div class="text-sm text-gray-500">WhatsApp Queue</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ local_time_format(now()) }}</div>
                        <div class="text-sm text-gray-500">Local Time ({{ app_timezone() }})</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Statistics -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Ticket Type -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue by Ticket Type</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Revenue breakdown by ticket type</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    @forelse($stats['revenue_by_ticket_type'] as $ticketType)
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                    <i class="fas fa-ticket-alt text-green-600"></i>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $ticketType->name }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($ticketType->count) }} registrations</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($ticketType->revenue) }}</div>
                            <div class="text-sm text-gray-500">@ Rp {{ number_format($ticketType->price) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500">No ticket types found</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Revenue by Race Category -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue by Race Category</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed revenue breakdown by race category</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    @forelse($stats['revenue_by_category'] as $category)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                                        <i class="fas fa-running text-indigo-600"></i>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500">Price: Rp {{ number_format($category->price) }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-medium text-gray-900">Rp {{ number_format($category->revenue) }}</div>
                                <div class="text-sm text-gray-500">Revenue received</div>
                            </div>
                        </div>
                        
                        <!-- Category breakdown -->
                        <div class="grid grid-cols-3 gap-4 mt-3">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($category->count) }}</div>
                                <div class="text-xs text-gray-500">Total Registrations</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($category->revenue / ($category->price > 0 ? $category->price : 1)) }}</div>
                                <div class="text-xs text-gray-500">Paid</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ number_format($category->count - ($category->revenue / ($category->price > 0 ? $category->price : 1))) }}</div>
                                <div class="text-xs text-gray-500">Unpaid</div>
                            </div>
                        </div>
                        
                        <!-- Potential revenue vs actual -->
                        <div class="mt-3">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Potential: Rp {{ number_format($category->count * $category->price) }}</span>
                                <span>Achievement: {{ $category->count > 0 ? round(($category->revenue / ($category->count * $category->price)) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $category->count > 0 ? round(($category->revenue / ($category->count * $category->price)) * 100, 1) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500">No race categories found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Common administrative tasks</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.ticket-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Add Ticket Type
                    </a>
                    <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-cogs mr-2"></i>
                        Manage Settings
                    </a>
                    <a href="{{ route('admin.unpaid-registrations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Check Unpaid
                    </a>
                    <a href="{{ route('admin.whatsapp-queue.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp Queue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
