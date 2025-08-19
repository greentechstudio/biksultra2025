@extends('layouts.admin')

@section('title', 'Collective Groups')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Collective Groups</h1>
            <div class="text-sm text-gray-600">
                Total: {{ count($groups) }} groups
            </div>
        </div>
    </div>

    @if(count($groups) > 0)
        <div class="space-y-6">
            @foreach($groups as $group)
                <div class="bg-white shadow rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $group['type'] }}
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $group['count'] }} participants
                                    </span>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $group['external_id'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Created: {{ $group['created_at']->format('d/m/Y H:i') }}
                                    @if($group['primary_user_id'])
                                        | Primary User ID: {{ $group['primary_user_id'] }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-semibold text-gray-900">
                                    Rp {{ number_format($group['total_amount'], 0, ',', '.') }}
                                </div>
                                <div class="mt-1">
                                    @if($group['payment_status'] === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Paid
                                        </span>
                                    @elseif($group['payment_status'] === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            ❓ {{ $group['payment_status'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($group['invoice_id'])
                            <div class="mt-3 flex space-x-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Invoice ID:</span>
                                    <span class="font-mono text-gray-700">{{ $group['invoice_id'] }}</span>
                                </div>
                                @if($group['invoice_url'])
                                    <div>
                                        <a href="{{ $group['invoice_url'] }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 underline">
                                            View Invoice
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="px-6 py-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Participants</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($group['users'] as $user)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-400">{{ $user->race_category }}</p>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <span class="text-xs text-gray-500">ID: {{ $user->id }}</span>
                                        </div>
                                    </div>
                                    @if($user->whatsapp_number)
                                        <p class="text-xs text-gray-400 mt-1">{{ $user->whatsapp_number }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-xs text-gray-500">
                            Last updated: {{ $group['users']->max('updated_at')->format('d/m/Y H:i') }}
                        </div>
                        <div class="flex space-x-2">
                            @if(!$group['invoice_id'])
                                <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                    Generate Invoice
                                </button>
                            @endif
                            <button class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 text-lg mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Collective Groups Found</h3>
                <p class="text-gray-500 mb-4">
                    There are no collective registrations yet. You can create collective groups through:
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('admin.collective-import.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        CSV Import
                    </a>
                    <a href="{{ route('register.kolektif') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Manual Registration
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
