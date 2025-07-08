@extends('layouts.admin')

@section('title', 'Recent Registrations')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Registrations</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">All registrations ordered by newest first</p>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($users as $user)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                    <i class="fas fa-user text-blue-600"></i>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                <div class="text-xs text-gray-400">{{ $user->whatsapp_number }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $user->race_category }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div class="flex flex-col items-end space-y-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->payment_confirmed) bg-green-100 text-green-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $user->payment_confirmed ? 'Paid' : 'Pending' }}
                                </span>
                                @if($user->whatsapp_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1"></i>
                                    WhatsApp Verified
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                    No registrations found
                </li>
                @endforelse
            </ul>
        </div>
        
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
