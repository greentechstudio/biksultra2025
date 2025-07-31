@extends('layouts.admin')

@section('title', 'Unpaid Registrations')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-yellow-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Total Unpaid</h5>
                        <h3 id="total-unpaid" class="text-2xl font-bold">0</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Within 24 Hours</h5>
                        <h3 id="within-6-hours" class="text-2xl font-bold">0</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-hourglass-half text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-red-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Over 24 Hours</h5>
                        <h3 id="over-6-hours" class="text-2xl font-bold">0</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-trash text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-green-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Auto Cleanup</h5>
                        <h6 id="cleanup-status" class="text-base font-medium">Active</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-robot text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Controls -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Management Controls</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Control unpaid registrations and system actions</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex flex-wrap gap-3">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700" onclick="refreshUnpaidStatus()">
                    <i class="fas fa-refresh mr-2"></i>Refresh
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" onclick="forceReminders()">
                    <i class="fas fa-bell mr-2"></i>Send Reminders
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700" onclick="forceCleanup()">
                    <i class="fas fa-trash mr-2"></i>Force Cleanup
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700" onclick="window.open('/admin/whatsapp-queue', '_blank')">
                    <i class="fas fa-cog mr-2"></i>Queue Management
                </button>
            </div>
        </div>
    </div>

    <!-- Unpaid Users Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Users with Pending Payments</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Monitor registrations awaiting payment</p>
        </div>
        <div class="border-t border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Elapsed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reminders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Reminder</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="unpaid-users" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Auto refresh every 2 minutes
setInterval(refreshUnpaidStatus, 120000);

// Initial load
refreshUnpaidStatus();

function refreshUnpaidStatus() {
    fetch('/admin/whatsapp-queue/unpaid-status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUnpaidStatus(data.data);
            }
        })
        .catch(error => {
            console.error('Error refreshing unpaid status:', error);
        });
}

function updateUnpaidStatus(data) {
    // Update overview cards
    document.getElementById('total-unpaid').textContent = data.total_unpaid;
    document.getElementById('within-6-hours').textContent = data.within_6_hours;
    document.getElementById('over-6-hours').textContent = data.over_6_hours;
    
    // Update users table
    const tbody = document.getElementById('unpaid-users');
    tbody.innerHTML = '';
    
    if (data.users_by_time_remaining.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="px-6 py-4 text-center text-green-600">🎉 No unpaid registrations!</td></tr>';
    } else {
        data.users_by_time_remaining.forEach(user => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            // Determine status color based on time remaining
            let statusClass = 'bg-green-100 text-green-800';
            let statusText = 'Safe';
            let timeRemainingClass = 'bg-green-100 text-green-800';
            
            if (user.minutes_elapsed > 1200) { // > 20 hours
                statusClass = 'bg-red-100 text-red-800';
                statusText = 'Critical';
                timeRemainingClass = 'bg-red-100 text-red-800';
            } else if (user.minutes_elapsed > 960) { // > 16 hours
                statusClass = 'bg-yellow-100 text-yellow-800';
                statusText = 'Warning';
                timeRemainingClass = 'bg-yellow-100 text-yellow-800';
            } else if (user.minutes_elapsed > 720) { // > 12 hours
                statusClass = 'bg-blue-100 text-blue-800';
                statusText = 'Monitor';
                timeRemainingClass = 'bg-blue-100 text-blue-800';
            }
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${user.name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.email}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.phone}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.category}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.registered_at}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.hours_elapsed}h</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${timeRemainingClass}">${user.time_remaining}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${user.reminders_sent}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs text-gray-500">${user.next_reminder_in}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${statusText}</span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
}

function forceReminders() {
    if (confirm('Send payment reminders to all eligible users?')) {
        fetch('/admin/whatsapp-queue/force-reminders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment reminders job dispatched successfully');
                refreshUnpaidStatus();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending reminders:', error);
            alert('Error sending reminders');
        });
    }
}

function forceCleanup() {
    if (confirm('⚠️ This will delete all registrations older than 24 hours without payment!\\n\\nAre you sure you want to proceed?')) {
        fetch('/admin/whatsapp-queue/force-cleanup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cleanup job dispatched successfully');
                refreshUnpaidStatus();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error forcing cleanup:', error);
            alert('Error forcing cleanup');
        });
    }
}
</script>
@endsection
