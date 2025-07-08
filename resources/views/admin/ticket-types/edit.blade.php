@extends('layouts.admin')

@section('title', 'Edit Ticket Type')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Edit Ticket Type</h1>
            <p class="mt-2 text-sm text-gray-700">Update ticket type settings and quota</p>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('admin.ticket-types.update', $ticketType) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Ticket Type Name</label>
                    <input type="text" name="name" id="name" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('name', $ticketType->name) }}" placeholder="e.g., Early Bird, Regular">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="race_category_id" class="block text-sm font-medium text-gray-700">Race Category</label>
                    <select name="race_category_id" id="race_category_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Category</option>
                        @foreach($raceCategories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('race_category_id', $ticketType->race_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} - Rp {{ number_format($category->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('race_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                    <input type="number" name="price" id="price" required min="0" step="1000"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('price', $ticketType->price) }}" placeholder="e.g., 100000">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quota" class="block text-sm font-medium text-gray-700">Quota (leave empty for unlimited)</label>
                    <input type="number" name="quota" id="quota" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('quota', $ticketType->quota) }}" placeholder="e.g., 300">
                    @error('quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                    <input type="datetime-local" name="start_date" id="start_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('start_date', $ticketType->start_date ? $ticketType->start_date->format('Y-m-d\TH:i') : '') }}">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                    <input type="datetime-local" name="end_date" id="end_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('end_date', $ticketType->end_date ? $ticketType->end_date->format('Y-m-d\TH:i') : '') }}">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" 
                                {{ old('is_active', $ticketType->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    
                    <div>
                        <label for="registered_count" class="block text-sm font-medium text-gray-700">Registered Count</label>
                        <input type="number" name="registered_count" id="registered_count" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('registered_count', $ticketType->registered_count) }}" readonly>
                        <p class="mt-1 text-sm text-gray-500">Current number of registered users</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.ticket-types.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Update Ticket Type
                </button>
            </div>
        </form>
    </div>

    <!-- Registration Statistics -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600">Total Registered</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $ticketType->registered_count }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600">Available Quota</div>
                    <div class="text-2xl font-bold text-green-900">
                        {{ $ticketType->quota ? ($ticketType->quota - $ticketType->registered_count) : 'Unlimited' }}
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-600">Utilization</div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $ticketType->quota ? round(($ticketType->registered_count / $ticketType->quota) * 100, 1) . '%' : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
