@extends('layouts.app')

@section('title', 'Unpaid Registrations Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-clock me-2"></i>Unpaid Registrations Management</h4>
                    <p class="mb-0 text-muted">Monitor and manage registrations with pending payments</p>
                </div>
                <div class="card-body">
                    <!-- Status Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Total Unpaid</h5>
                                            <h3 id="total-unpaid">0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Within 24 Hours</h5>
                                            <h3 id="within-6-hours">0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-hourglass-half fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Over 24 Hours</h5>
                                            <h3 id="over-6-hours">0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-trash fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Auto Cleanup</h5>
                                            <h6 id="cleanup-status">Active</h6>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-robot fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Management Controls -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success" onclick="refreshUnpaidStatus()">
                                    <i class="fas fa-refresh me-1"></i>Refresh
                                </button>
                                <button type="button" class="btn btn-primary" onclick="forceReminders()">
                                    <i class="fas fa-bell me-1"></i>Send Reminders
                                </button>
                                <button type="button" class="btn btn-warning" onclick="forceCleanup()">
                                    <i class="fas fa-trash me-1"></i>Force Cleanup
                                </button>
                                <button type="button" class="btn btn-info" onclick="window.open('/admin/whatsapp-queue', '_blank')">
                                    <i class="fas fa-cog me-1"></i>Queue Management
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Unpaid Users Table -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Users with Pending Payments</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Category</th>
                                            <th>Registered</th>
                                            <th>Time Elapsed</th>
                                            <th>Time Remaining</th>
                                            <th>Reminders Sent</th>
                                            <th>Next Reminder</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unpaid-users">
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        tbody.innerHTML = '<tr><td colspan="10" class="text-center text-success">🎉 No unpaid registrations!</td></tr>';
    } else {
        data.users_by_time_remaining.forEach(user => {
            const row = document.createElement('tr');
            
            // Determine status color based on time remaining
            let statusClass = 'success';
            let statusText = 'Safe';
            
            if (user.minutes_elapsed > 1200) { // > 20 hours
                statusClass = 'danger';
                statusText = 'Critical';
            } else if (user.minutes_elapsed > 960) { // > 16 hours
                statusClass = 'warning';
                statusText = 'Warning';
            } else if (user.minutes_elapsed > 720) { // > 12 hours
                statusClass = 'info';
                statusText = 'Monitor';
            }
            
            row.innerHTML = `
                <td><strong>${user.name}</strong></td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.category}</td>
                <td>${user.registered_at}</td>
                <td>${user.hours_elapsed}h</td>
                <td><span class="badge bg-${statusClass}">${user.time_remaining}</span></td>
                <td>${user.reminders_sent}</td>
                <td><small>${user.next_reminder_in}</small></td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
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
    if (confirm('⚠️ This will delete all registrations older than 24 hours without payment!\n\nAre you sure you want to proceed?')) {
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

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.875em;
}

.card-body h3 {
    margin-bottom: 0;
    font-weight: bold;
}

.table-responsive {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection
