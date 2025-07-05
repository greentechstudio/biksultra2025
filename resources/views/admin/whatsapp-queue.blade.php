@extends('layouts.app')

@section('title', 'WhatsApp Queue Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fab fa-whatsapp me-2"></i>WhatsApp Queue Management</h4>
                </div>
                <div class="card-body">
                    <!-- Queue Status -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Queue Length</h5>
                                            <h3 id="queue-length">{{ $status['queue_length'] }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-{{ $status['is_processing'] ? 'success' : 'warning' }} text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Status</h5>
                                            <h6 id="processing-status">{{ $status['is_processing'] ? 'Processing' : 'Idle' }}</h6>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-{{ $status['is_processing'] ? 'play' : 'pause' }} fa-2x"></i>
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
                                            <h5 class="card-title">Est. Time</h5>
                                            <h6 id="estimated-time">{{ gmdate('H:i:s', $status['estimated_time']) }}</h6>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
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
                                            <h5 class="card-title">Failed</h5>
                                            <h3 id="failed-count">{{ $status['failed_messages'] }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Controls -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success" onclick="refreshStatus()">
                                    <i class="fas fa-refresh me-1"></i>Refresh
                                </button>
                                <button type="button" class="btn btn-primary" onclick="forceProcess()">
                                    <i class="fas fa-play me-1"></i>Force Process
                                </button>
                                <button type="button" class="btn btn-warning" onclick="clearQueue()">
                                    <i class="fas fa-trash me-1"></i>Clear Queue
                                </button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#testModal">
                                    <i class="fas fa-paper-plane me-1"></i>Send Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Next Messages -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Next Messages in Queue</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Queue ID</th>
                                            <th>Phone Number</th>
                                            <th>Priority</th>
                                            <th>Created</th>
                                            <th>Attempts</th>
                                            <th>Message Preview</th>
                                        </tr>
                                    </thead>
                                    <tbody id="next-messages">
                                        @forelse($status['next_messages'] as $message)
                                        <tr>
                                            <td><code>{{ $message['id'] }}</code></td>
                                            <td>{{ $message['phone_number'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $message['priority'] === 'high' ? 'danger' : ($message['priority'] === 'normal' ? 'primary' : 'secondary') }}">
                                                    {{ ucfirst($message['priority']) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($message['created_at'])->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $message['attempts'] }}/{{ $message['max_attempts'] }}</td>
                                            <td>{{ Str::limit($message['message'], 50) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No messages in queue</td>
                                        </tr>
                                        @endforelse
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

<!-- Test Message Modal -->
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="testForm">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" 
                               placeholder="6281234567890" required>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="high">High</option>
                            <option value="normal" selected>Normal</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" 
                                  placeholder="Test message from queue system..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendTest()">Send Test</button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh every 30 seconds
setInterval(refreshStatus, 30000);

function refreshStatus() {
    fetch('/admin/whatsapp-queue/status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatus(data.data);
            }
        })
        .catch(error => {
            console.error('Error refreshing status:', error);
        });
}

function updateStatus(status) {
    document.getElementById('queue-length').textContent = status.queue_length;
    document.getElementById('processing-status').textContent = status.is_processing ? 'Processing' : 'Idle';
    document.getElementById('estimated-time').textContent = new Date(status.estimated_time * 1000).toISOString().substr(11, 8);
    document.getElementById('failed-count').textContent = status.failed_messages;
    
    // Update next messages table
    const tbody = document.getElementById('next-messages');
    tbody.innerHTML = '';
    
    if (status.next_messages.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No messages in queue</td></tr>';
    } else {
        status.next_messages.forEach(message => {
            const row = document.createElement('tr');
            const priorityClass = message.priority === 'high' ? 'danger' : (message.priority === 'normal' ? 'primary' : 'secondary');
            const createdAt = new Date(message.created_at * 1000).toLocaleString();
            
            row.innerHTML = `
                <td><code>${message.id}</code></td>
                <td>${message.phone_number}</td>
                <td><span class="badge bg-${priorityClass}">${message.priority.charAt(0).toUpperCase() + message.priority.slice(1)}</span></td>
                <td>${createdAt}</td>
                <td>${message.attempts}/${message.max_attempts}</td>
                <td>${message.message.substring(0, 50)}...</td>
            `;
            tbody.appendChild(row);
        });
    }
}

function forceProcess() {
    if (confirm('Are you sure you want to force process the queue?')) {
        fetch('/admin/whatsapp-queue/force-process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Queue processing started successfully');
                refreshStatus();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error forcing queue process:', error);
            alert('Error processing queue');
        });
    }
}

function clearQueue() {
    if (confirm('Are you sure you want to clear the entire queue? This action cannot be undone.')) {
        fetch('/admin/whatsapp-queue/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Queue cleared successfully');
                refreshStatus();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error clearing queue:', error);
            alert('Error clearing queue');
        });
    }
}

function sendTest() {
    const form = document.getElementById('testForm');
    const formData = new FormData(form);
    
    fetch('/admin/whatsapp-queue/send-test', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test message queued successfully. Queue ID: ' + data.queue_id);
            bootstrap.Modal.getInstance(document.getElementById('testModal')).hide();
            form.reset();
            refreshStatus();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error sending test message:', error);
        alert('Error sending test message');
    });
}
</script>
@endsection
