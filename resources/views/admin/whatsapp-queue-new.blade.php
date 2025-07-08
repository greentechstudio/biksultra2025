@extends('layouts.admin')

@section('title', 'WhatsApp Queue Management')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Queue Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Queue Length</h5>
                        <h3 id="queue-length" class="text-2xl font-bold">{{ $status['queue_length'] }}</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-3xl opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden shadow rounded-lg {{ $status['is_processing'] ? 'bg-green-500' : 'bg-yellow-500' }} text-white">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Status</h5>
                        <h6 id="processing-status" class="text-xl font-bold">{{ $status['is_processing'] ? 'Processing' : 'Idle' }}</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $status['is_processing'] ? 'play' : 'pause' }} text-3xl opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-indigo-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Est. Time</h5>
                        <h6 id="estimated-time" class="text-xl font-bold">{{ gmdate('H:i:s', $status['estimated_time']) }}</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-3xl opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-red-500 text-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-medium">Failed</h5>
                        <h3 id="failed-count" class="text-2xl font-bold">{{ $status['failed_messages'] }}</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-3xl opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Controls -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Queue Controls</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage WhatsApp message queue</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex flex-wrap gap-3">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700" onclick="refreshStatus()">
                    <i class="fas fa-refresh mr-2"></i>Refresh
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700" onclick="forceProcess()">
                    <i class="fas fa-play mr-2"></i>Force Process
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700" onclick="clearQueue()">
                    <i class="fas fa-trash mr-2"></i>Clear Queue
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700" onclick="openTestModal()">
                    <i class="fas fa-paper-plane mr-2"></i>Send Test
                </button>
            </div>
        </div>
    </div>

    <!-- Queue Messages Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Next Messages in Queue</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Upcoming messages to be processed</p>
        </div>
        <div class="border-t border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message Preview</th>
                    </tr>
                </thead>
                <tbody id="next-messages" class="bg-white divide-y divide-gray-200">
                    @forelse($status['next_messages'] as $message)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $message['id'] }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $message['phone_number'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $message['priority'] === 'high' ? 'bg-red-100 text-red-800' : 
                                   ($message['priority'] === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($message['priority']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::createFromTimestamp($message['created_at'])->format('d/m/Y H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $message['attempts'] }}/{{ $message['max_attempts'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($message['message'], 50) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No messages in queue</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Test Message Modal -->
<div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Test Message</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeTestModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="testForm" class="space-y-4">
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="6281234567890" required>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select id="priority" name="priority" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="high">High</option>
                        <option value="normal" selected>Normal</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="5" 
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Test message from queue system..." required></textarea>
                </div>
            </form>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" onclick="closeTestModal()">
                    Cancel
                </button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="sendTest()">
                    Send Test
                </button>
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
    
    // Update processing status card color
    const statusCard = document.getElementById('processing-status').closest('.overflow-hidden');
    if (status.is_processing) {
        statusCard.className = statusCard.className.replace('bg-yellow-500', 'bg-green-500');
    } else {
        statusCard.className = statusCard.className.replace('bg-green-500', 'bg-yellow-500');
    }
    
    // Update next messages table
    const tbody = document.getElementById('next-messages');
    tbody.innerHTML = '';
    
    if (status.next_messages.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No messages in queue</td></tr>';
    } else {
        status.next_messages.forEach(message => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            const priorityClass = message.priority === 'high' ? 'bg-red-100 text-red-800' : 
                                 (message.priority === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800');
            const createdAt = new Date(message.created_at * 1000).toLocaleString();
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <code class="bg-gray-100 px-2 py-1 rounded text-sm">${message.id}</code>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${message.phone_number}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${priorityClass}">
                        ${message.priority.charAt(0).toUpperCase() + message.priority.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${createdAt}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${message.attempts}/${message.max_attempts}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">${message.message.substring(0, 50)}...</div>
                </td>
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

function openTestModal() {
    document.getElementById('testModal').classList.remove('hidden');
}

function closeTestModal() {
    document.getElementById('testModal').classList.add('hidden');
    document.getElementById('testForm').reset();
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
            closeTestModal();
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

// Close modal when clicking outside
document.getElementById('testModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTestModal();
    }
});
</script>
@endsection
