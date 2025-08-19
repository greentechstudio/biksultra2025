@extends('layouts.admin')

@section('title', 'Collective Import')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Collective Import</h1>
            <p class="mt-2 text-sm text-gray-600">Import multiple participants at once using Excel file (No minimum participant limit for admin)</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Import Successful!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>{{ session('success') }}</p>
                        @if(session('imported_count'))
                            <p class="mt-1"><strong>Imported:</strong> {{ session('imported_count') }} participants</p>
                        @endif
                        @if(session('invoice_url'))
                            <p class="mt-1">
                                <strong>Invoice URL:</strong> 
                                <a href="{{ session('invoice_url') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                    View Invoice
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Import Errors</h3>
                    <div class="mt-2 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p class="mt-1">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Import Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Import Excel File</h2>
                    </div>
                    
                    <form action="{{ route('admin.collective-import.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Group Name -->
                        <div>
                            <label for="group_name" class="block text-sm font-medium text-gray-700">Group Name</label>
                            <input type="text" 
                                   name="group_name" 
                                   id="group_name" 
                                   required
                                   value="{{ old('group_name') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                   placeholder="e.g., Company Team A, Running Club Jakarta">
                            <p class="mt-1 text-xs text-gray-500">This will be used as the group identifier for the collective registration</p>
                        </div>

                        <!-- Excel File -->
                        <div>
                            <label for="excel_file" class="block text-sm font-medium text-gray-700">Excel File</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                            <span>Upload CSV file</span>
                                            <input id="excel_file" name="excel_file" type="file" class="sr-only" accept=".csv" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">CSV up to 10MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Generate Invoice Option -->
                        <div class="flex items-center">
                            <input id="generate_invoice" 
                                   name="generate_invoice" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('generate_invoice') ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="generate_invoice" class="ml-2 block text-sm text-gray-900">
                                Generate collective invoice immediately after import
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Import Participants
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions & Template -->
            <div class="space-y-6">
                <!-- Excel Template -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">CSV Template</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Download the CSV template to ensure proper format</p>
                        <a href="{{ route('admin.collective-import.template') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download CSV Template
                        </a>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Instructions</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-600 space-y-3">
                            <div>
                                <h4 class="font-medium text-gray-900">Required Columns:</h4>
                                <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                                    <li>Name, BIB Name, Email</li>
                                    <li>WhatsApp Number</li>
                                    <li>Birth Place, Birth Date</li>
                                    <li>Gender (Pria/Wanita)</li>
                                    <li>Address, City, Province</li>
                                    <li>Race Category, Jersey Size</li>
                                    <li>Blood Type, Emergency Contact</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-900">Important Notes:</h4>
                                <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                                    <li><strong>CSV format only</strong> - Save Excel as CSV before upload</li>
                                    <li><strong>No minimum participant limit</strong> for admin import</li>
                                    <li>All email addresses must be unique</li>
                                    <li>Birth date format: YYYY-MM-DD</li>
                                    <li>WhatsApp number with country code</li>
                                    <li>Default password: "password123"</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900">Available Race Categories:</h4>
                                <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                                    @foreach($raceCategories as $category)
                                        <li>{{ $category }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Features -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Admin Privileges</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>CSV format required</strong> - Easy to create from Excel</li>
                                        <li>Can import 1-100 participants</li>
                                        <li>Bypasses normal collective validation</li>
                                        <li>Auto-generates External IDs</li>
                                        <li>Excel users: Save as CSV format first</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File upload preview
document.getElementById('excel_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileInfo = document.createElement('div');
        fileInfo.className = 'mt-2 text-xs text-gray-600';
        fileInfo.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        
        // Remove existing file info
        const existing = e.target.parentNode.parentNode.parentNode.querySelector('.file-info');
        if (existing) existing.remove();
        
        fileInfo.className += ' file-info';
        e.target.parentNode.parentNode.parentNode.appendChild(fileInfo);
    }
});
</script>
@endsection
