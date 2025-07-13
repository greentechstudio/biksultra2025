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
                <!-- Total Potential Revenue (from unpaid only) -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Total Potential</h4>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($stats['unpaid_potential_revenue'], 0, ',', '.') }}</p>
                            <p class="text-sm mt-1 opacity-90">From {{ number_format($stats['pending_registrations']) }} pending payments</p>
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

                <!-- Total Maximum Potential -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Total Maximum</h4>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($stats['total_potential_revenue'], 0, ',', '.') }}</p>
                            <p class="text-sm mt-1 opacity-90">{{ number_format($stats['total_registrations']) }} total registrations</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-calculator text-4xl opacity-80"></i>
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
                                    <div class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-tshirt mr-1"></i>{{ $registration->jersey_size ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <!-- Ticket Type Badge -->
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($registration->ticket_type && str_contains(strtolower($registration->ticket_type), 'early'))
                                        bg-green-100 text-green-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $registration->ticket_type ?? 'Regular' }}
                                </span>
                                
                                <!-- Payment Status -->
                                {{-- Debug: Status = {{ $registration->status }} --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($registration->status === 'paid') bg-green-100 text-green-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($registration->status === 'paid')
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    @else
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    @endif
                                </span>
                                
                                <!-- Race Category -->
                                <div class="text-sm text-gray-500 font-medium">
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Category Statistics by Ticket Type</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Registration count by race category and ticket type (Early Bird vs Regular)</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    @forelse($stats['category_stats'] as $categoryTicket)
                    @if($categoryTicket->users_count > 0)
                    <div class="flex items-center justify-between mb-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full 
                                    @if(str_contains(strtolower($categoryTicket->ticket_type_name), 'early')) bg-green-100 @else bg-blue-100 @endif">
                                    <i class="fas fa-running 
                                        @if(str_contains(strtolower($categoryTicket->ticket_type_name), 'early')) text-green-600 @else text-blue-600 @endif"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $categoryTicket->category_name }}
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if(str_contains(strtolower($categoryTicket->ticket_type_name), 'early')) 
                                                    bg-green-100 text-green-800 
                                                @else 
                                                    bg-blue-100 text-blue-800 
                                                @endif">
                                                {{ $categoryTicket->ticket_type_name }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Rp {{ number_format($categoryTicket->price, 0, ',', '.') }}
                                            @if($categoryTicket->revenue > 0)
                                            • Revenue: Rp {{ number_format($categoryTicket->revenue, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <!-- Total Registrations -->
                            <div class="text-center">
                                <div class="text-xl font-bold text-gray-900">{{ $categoryTicket->users_count }}</div>
                                <div class="text-xs text-gray-500">Total</div>
                            </div>
                            
                            <!-- Paid -->
                            <div class="text-center">
                                <div class="text-xl font-bold text-green-600">{{ $categoryTicket->paid_count }}</div>
                                <div class="text-xs text-green-600">Paid</div>
                            </div>
                            
                            <!-- Pending -->
                            <div class="text-center">
                                <div class="text-xl font-bold text-yellow-600">{{ $categoryTicket->pending_count }}</div>
                                <div class="text-xs text-yellow-600">Pending</div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-20">
                                @php
                                $percentage = $categoryTicket->users_count > 0 ? round(($categoryTicket->paid_count / $categoryTicket->users_count) * 100) : 0;
                                @endphp
                                <div class="bg-gray-200 rounded-full h-2 mb-1">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="text-xs text-center text-gray-500">{{ $percentage }}%</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p>No category statistics available</p>
                    </div>
                    @endforelse
                    
                    <!-- Summary Row -->
                    @if($stats['category_stats']->sum('users_count') > 0)
                    <div class="border-t border-gray-300 pt-4 mt-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                                        <i class="fas fa-chart-pie text-purple-600"></i>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">TOTAL ALL CATEGORIES</div>
                                    <div class="text-xs text-gray-500">Combined statistics</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-gray-900">{{ $stats['category_stats']->sum('users_count') }}</div>
                                    <div class="text-xs text-gray-500">Total</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-green-600">{{ $stats['category_stats']->sum('paid_count') }}</div>
                                    <div class="text-xs text-green-600">Paid</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-yellow-600">{{ $stats['category_stats']->sum('pending_count') }}</div>
                                    <div class="text-xs text-yellow-600">Pending</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-purple-600">Rp {{ number_format($stats['category_stats']->sum('revenue'), 0, ',', '.') }}</div>
                                    <div class="text-xs text-purple-600">Revenue</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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

    <!-- Demographics Statistics & Quick Actions -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Demographics by Age Group Chart -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Demographics by Age Group & Race Category</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Registration distribution by age groups and race categories (Paid users only)</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    <!-- Chart Container -->
                    <div class="relative h-96 mb-6">
                        <div id="ageGroupChart"></div>
                    </div>
                    
                    <!-- Age Groups Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        @forelse($stats['demographics_stats']['age_groups'] as $ageGroup)
                        @if($ageGroup->count > 0)
                        <div class="text-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="text-lg font-bold text-green-600">{{ $ageGroup->count }}</div>
                            <div class="text-sm font-medium text-gray-700">{{ $ageGroup->group }} years</div>
                            <div class="text-xs text-gray-500">Born: {{ $ageGroup->birth_year_range }}</div>
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-check-circle"></i> Paid only
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                            <p>No age demographics available</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Detailed Age Distribution Table -->
                    <div class="mt-8 overflow-x-auto">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Detailed Age Distribution by Race Category</h4>
                        <table class="min-w-full table-auto border-collapse border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Age Group</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700">Birth Year Range</th>
                                    @foreach($stats['demographics_stats']['race_categories'] as $category)
                                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700">{{ $category->category }}</th>
                                    @endforeach
                                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($stats['demographics_stats']['age_groups'] as $ageGroup)
                                @if($ageGroup->count > 0)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-900">
                                        {{ $ageGroup->group }} years
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                        {{ $ageGroup->birth_year_range }}
                                    </td>
                                    @foreach($stats['demographics_stats']['race_categories'] as $category)
                                    <td class="border border-gray-300 px-4 py-2 text-center text-sm text-gray-700">
                                        {{ $stats['demographics_stats']['chart_data'][$ageGroup->group][$category->category] ?? 0 }}
                                    </td>
                                    @endforeach
                                    <td class="border border-gray-300 px-4 py-2 text-center text-sm font-bold text-gray-900">
                                        {{ $ageGroup->count }}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Common administrative tasks</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    <div class="space-y-4">
                        <a href="{{ route('admin.ticket-types.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Add Ticket Type
                        </a>
                        <a href="{{ route('admin.settings') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-cogs mr-2"></i>
                            Manage Settings
                        </a>
                        <a href="{{ route('admin.unpaid-registrations.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Check Unpaid
                        </a>
                        <a href="{{ route('admin.whatsapp-queue.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fab fa-whatsapp mr-2"></i>
                            WhatsApp Queue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Master A & Master B Tables -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Master A: Age 40-49, Category 21K -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Master A Register</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Users aged 40-49 in 21K category (Paid only)</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        @if(count($stats['master_a_users']) > 0)
                            <div class="mb-4">
                                <div class="text-sm text-gray-600">
                                    Total Paid: <span class="font-medium text-green-600">{{ count($stats['master_a_users']) }}</span> users
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table id="masterATable" class="min-w-full divide-y divide-gray-200 display responsive nowrap" style="width:100%">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Age
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Birth Year
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stats['master_a_users'] as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $user->age }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs text-gray-500">{{ $user->birth_year }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p>No Master A users found</p>
                                <p class="text-sm">No paid users aged 40-49 in 21K category</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Master B: Age 50+, Category 21K -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Master B Register</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Users aged 50+ in 21K category (Paid only)</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        @if(count($stats['master_b_users']) > 0)
                            <div class="mb-4">
                                <div class="text-sm text-gray-600">
                                    Total Paid: <span class="font-medium text-green-600">{{ count($stats['master_b_users']) }}</span> users
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table id="masterBTable" class="min-w-full divide-y divide-gray-200 display responsive nowrap" style="width:100%">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Age
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Birth Year
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stats['master_b_users'] as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $user->age }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs text-gray-500">{{ $user->birth_year }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p>No Master B users found</p>
                                <p class="text-sm">No paid users aged 50+ in 21K category</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gabungan 5K, 10K, 21K per Usia - Grafik Interaktif -->
    <div class="bg-white shadow rounded-lg mt-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Gabungan 5K, 10K, 21K per Kelompok Usia</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Grafik interaktif jumlah peserta gabungan 5K, 10K, dan 21K berdasarkan kelompok usia (Paid users only)</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:px-6">
                <div class="relative h-96 mb-6">
                    <div id="ageGroupCombinedChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Debug: Check if ApexCharts is loaded
        console.log('ApexCharts loaded:', typeof ApexCharts !== 'undefined');
        console.log('ApexCharts object:', ApexCharts);
        
        // Get data from Laravel
        const chartData = {!! json_encode($stats['demographics_stats']['chart_data']) !!};
        const ageGroupsData = {!! json_encode($stats['demographics_stats']['age_groups']) !!};
        const raceCategories = {!! json_encode(collect($stats['demographics_stats']['race_categories'])->pluck('category')->toArray()) !!};
        
        console.log('Demographics stats full:', {!! json_encode($stats['demographics_stats']) !!});
        console.log('chartData from Laravel:', chartData);
        console.log('ageGroupsData:', ageGroupsData);
        console.log('raceCategories:', raceCategories);
        
        // Check if chartData is empty
        if (!chartData || Object.keys(chartData).length === 0) {
            console.error('chartData is empty or undefined!');
            return;
        }
        
        // Prepare data for ApexCharts
        const ageGroups = Object.keys(chartData);
        console.log('ageGroups:', ageGroups);
        
        // Check if we have valid data
        if (!ageGroups || ageGroups.length === 0) {
            console.error('No age groups found!');
            return;
        }
        
        if (!raceCategories || raceCategories.length === 0) {
            console.error('No race categories found!');
            return;
        }
        
        // Check if canvas elements exist
        const chart1Element = document.getElementById('ageGroupChart');
        const chart2Element = document.getElementById('ageGroupCombinedChart');
        
        if (!chart1Element) {
            console.error('Canvas ageGroupChart not found!');
            return;
        }
        
        if (!chart2Element) {
            console.error('Canvas ageGroupCombinedChart not found!');
            return;
        }
        
        // Prepare series data for grouped bar chart
        const series = raceCategories.map((category, index) => {
            const data = ageGroups.map(ageGroup => {
                const value = chartData[ageGroup] && chartData[ageGroup][category] ? chartData[ageGroup][category] : 0;
                return value;
            });
            
            return {
                name: category,
                data: data
            };
        });
        
        console.log('Series for first chart:', series);
        
        // Check if series have data
        const hasData = series.some(serie => serie.data.some(value => value > 0));
        console.log('Chart has data:', hasData);
        
        if (!hasData) {
            console.warn('No data found for charts - all values are 0');
        }
        
        // Colors for charts
        const colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Yellow
            '#EF4444', // Red
            '#8B4513', // Brown
            '#A855F7', // Purple
            '#EC4899', // Pink
            '#0EA5E9'  // Sky
        ];
        
        // First Chart: Demographics by Age Group & Race Category
        const options1 = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            series: series,
            colors: colors,
            xaxis: {
                categories: ageGroups,
                title: {
                    text: 'Age Groups'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Participants'
                }
            },
            title: {
                text: 'Demographics by Age Group & Race Category',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'center'
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(value, { series, seriesIndex, dataPointIndex }) {
                        const ageGroup = ageGroups[dataPointIndex];
                        const category = raceCategories[seriesIndex];
                        const groupInfo = ageGroupsData.find(g => g.group === ageGroup);
                        const birthYears = groupInfo ? groupInfo.birth_year_range : 'N/A';
                        
                        return `<div>
                            <strong>${category}:</strong> ${value} participants<br>
                            <small>Age Group: ${ageGroup} years<br>
                            Born: ${birthYears}</small>
                        </div>`;
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    endingShape: 'rounded',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: true,
                borderColor: 'rgba(0, 0, 0, 0.1)',
                strokeDashArray: 0,
                position: 'back'
            }
        };
        
        console.log('Creating first chart with options:', options1);
        try {
            const chart1 = new ApexCharts(chart1Element, options1);
            chart1.render();
            console.log('First chart created successfully');
        } catch (error) {
            console.error('Error creating first chart:', error);
        }
        
        // Second Chart: Combined 5K, 10K, 21K per Age Group
        const combinedData = ageGroups.map(ageGroup => {
            let total = 0;
            ['5K', '10K', '21K'].forEach(cat => {
                if (chartData[ageGroup] && chartData[ageGroup][cat]) {
                    total += chartData[ageGroup][cat];
                }
            });
            return total;
        });
        
        console.log('Combined data:', combinedData);
        
        const options2 = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            series: [{
                name: 'Total Peserta (5K, 10K, 21K)',
                data: combinedData
            }],
            colors: ['#3B82F6'],
            xaxis: {
                categories: ageGroups,
                title: {
                    text: 'Kelompok Usia'
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Peserta'
                }
            },
            title: {
                text: 'Gabungan Peserta 5K, 10K, 21K per Usia',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            legend: {
                show: false
            },
            tooltip: {
                y: {
                    formatter: function(value, { dataPointIndex }) {
                        const ageGroup = ageGroups[dataPointIndex];
                        const groupInfo = ageGroupsData.find(g => g.group === ageGroup);
                        const birthYears = groupInfo ? groupInfo.birth_year_range : 'N/A';
                        
                        return `<div>
                            <strong>Total peserta:</strong> ${value}<br>
                            <small>Usia: ${ageGroup} tahun<br>
                            Lahir: ${birthYears}</small>
                        </div>`;
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded',
                    borderRadius: 6
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val > 0 ? val : '';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: ['#fff']
                }
            },
            grid: {
                show: true,
                borderColor: 'rgba(0, 0, 0, 0.1)',
                strokeDashArray: 0,
                position: 'back'
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.1
                    }
                }
            }
        };
        
        console.log('Creating combined chart with options:', options2);
        try {
            const chart2 = new ApexCharts(chart2Element, options2);
            chart2.render();
            console.log('Combined chart created successfully');
        } catch (error) {
            console.error('Error creating combined chart:', error);
        }
        
    } catch (globalError) {
        console.error('Global error in ApexCharts initialization:', globalError);
    }
});

// Initialize DataTables for Master A and Master B
$(document).ready(function() {
    // Master A DataTable
    if ($('#masterATable').length) {
        $('#masterATable').DataTable({
            pageLength: 15,
            responsive: true,
            order: [[0, 'asc']], // Sort by name
            language: {
                search: "Search Master A:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ Master A users",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            columnDefs: [
                {
                    targets: [4], // Status column
                    orderable: false
                }
            ],
            dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4"<"flex-shrink-0"l><"flex-grow"f>>rtip'
        });
    }

    // Master B DataTable
    if ($('#masterBTable').length) {
        $('#masterBTable').DataTable({
            pageLength: 15,
            responsive: true,
            order: [[0, 'asc']], // Sort by name
            language: {
                search: "Search Master B:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ Master B users",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            columnDefs: [
                {
                    targets: [4], // Status column
                    orderable: false
                }
            ],
            dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4"<"flex-shrink-0"l><"flex-grow"f>>rtip'
        });
    }
});
</script>
@endpush

@endsection
