# 📱 WhatsApp Queue System - 10 Second Delay

## Overview
Sistem antrian pesan WhatsApp yang mengatur pengiriman pesan dengan delay 10 detik antar pesan. Sistem ini mencegah spam dan memastikan pesan terkirim dengan baik meskipun ada banyak registrasi bersamaan.

## Features

### 1. Message Queuing
- **Queue Management**: Semua pesan disimpan dalam antrian
- **Priority Support**: High, Normal, Low priority
- **Automatic Processing**: Queue diproses otomatis
- **10-Second Delay**: Jeda 10 detik antar pesan

### 2. Concurrent Registration Handling
- **Multiple Users**: Menangani registrasi bersamaan
- **Queue Order**: Pesan diproses berurutan
- **Priority First**: Pesan prioritas tinggi didahulukan
- **No Message Loss**: Tidak ada pesan yang hilang

### 3. Error Handling & Retry
- **Retry Logic**: Maksimal 3 kali percobaan
- **Failed Message Storage**: Pesan gagal disimpan untuk review
- **Automatic Retry**: Retry otomatis dengan delay
- **Manual Intervention**: Admin dapat intervensi manual

### 4. Real-time Monitoring
- **Queue Status**: Status antrian real-time
- **Processing Progress**: Progress pemrosesan
- **Failed Messages**: Tracking pesan gagal
- **Estimated Time**: Perkiraan waktu selesai

## Architecture

### 1. WhatsAppQueueService
```php
class WhatsAppQueueService
{
    const DELAY_SECONDS = 10; // 10 detik delay
    
    // Add message to queue
    public function addToQueue($phoneNumber, $message, $priority = 'normal')
    
    // Process queue with 10-second delay
    public function processQueue()
    
    // Get queue status
    public function getQueueStatus()
}
```

### 2. SendWhatsAppMessage Job
```php
class SendWhatsAppMessage implements ShouldQueue
{
    // Background job untuk memproses queue
    public function handle()
    {
        $queueService->processQueue();
    }
}
```

### 3. WhatsAppService Integration
```php
class WhatsAppService
{
    // Queue message instead of direct send
    public function queueMessage($phoneNumber, $message, $priority = 'normal')
    
    // Direct send (untuk urgent messages)
    public function sendMessage($phoneNumber, $message)
}
```

## Usage Examples

### 1. Registration Flow
```php
// Saat user registrasi
$whatsappService = app(WhatsAppService::class);

// Payment link (high priority)
$whatsappService->queueMessage(
    $user->whatsapp_number,
    $paymentMessage,
    'high'
);

// Activation message (normal priority)
$whatsappService->queueMessage(
    $user->whatsapp_number,
    $activationMessage,
    'normal'
);
```

### 2. Bulk Registration Scenario
```
User 1 registers at 10:00:00 -> Message queued
User 2 registers at 10:00:01 -> Message queued
User 3 registers at 10:00:02 -> Message queued
User 4 registers at 10:00:03 -> Message queued
User 5 registers at 10:00:04 -> Message queued

Processing:
10:00:05 -> User 1 message sent
10:00:15 -> User 2 message sent  (10 seconds later)
10:00:25 -> User 3 message sent  (10 seconds later)
10:00:35 -> User 4 message sent  (10 seconds later)
10:00:45 -> User 5 message sent  (10 seconds later)
```

### 3. Priority Handling
```
Queue: [High Priority Msg, Normal Msg 1, Normal Msg 2, High Priority Msg]
Processing Order: High Priority Msg → High Priority Msg → Normal Msg 1 → Normal Msg 2
```

## Configuration

### 1. Environment Variables
```env
WHATSAPP_API_KEY=your_api_key
WHATSAPP_SENDER=628114040707
WHATSAPP_QUEUE_DELAY=10  # seconds
```

### 2. Queue Settings
```php
// WhatsAppQueueService.php
const DELAY_SECONDS = 10;        // 10 detik delay
const MAX_ATTEMPTS = 3;          // Maksimal 3 percobaan
const TIMEOUT_MINUTES = 30;      // Timeout processing
```

## Monitoring & Management

### 1. Queue Status Dashboard
- **URL**: `/admin/whatsapp-queue`
- **Features**: Real-time monitoring, queue control
- **Metrics**: Queue length, processing status, failed messages

### 2. API Endpoints
```php
GET  /admin/whatsapp-queue/status      // Get queue status
POST /admin/whatsapp-queue/clear       // Clear queue
POST /admin/whatsapp-queue/force-process // Force process
POST /admin/whatsapp-queue/send-test   // Send test message
```

### 3. Queue Status Response
```json
{
    "queue_length": 5,
    "is_processing": true,
    "failed_messages": 0,
    "estimated_time": 50,
    "next_messages": [...]
}
```

## Error Handling

### 1. Failed Message Retry
```php
// Automatic retry up to 3 times
if ($attempts < $maxAttempts) {
    // Add back to queue for retry
    $queue[] = $queueItem;
} else {
    // Store as failed message
    $this->storeFailedMessage($queueItem);
}
```

### 2. Failed Message Storage
```php
// Failed messages stored for manual review
$failedMessages = [
    'queue_id' => 'unique_id',
    'phone_number' => '6281234567890',
    'message' => 'Original message',
    'attempts' => 3,
    'failed_at' => timestamp
];
```

## Performance Considerations

### 1. Memory Usage
- Queue stored in Cache (Redis/File)
- Automatic cleanup of old messages
- Memory efficient processing

### 2. Processing Time
- 10 seconds per message minimum
- Queue processing in background
- Non-blocking user experience

### 3. Scalability
- Handles hundreds of concurrent registrations
- Automatic queue management
- Failover support

## Testing

### 1. Test Files
- **test-whatsapp-queue.html**: Interactive testing interface
- **test-whatsapp-queue.bat**: Automated test script
- **WhatsAppQueueController**: API testing endpoints

### 2. Test Scenarios
```bash
# Run test
.\test-whatsapp-queue.bat

# Test concurrent registrations
# Simulate 5 users registering simultaneously
# Monitor 10-second delays
# Check priority handling
```

### 3. Manual Testing
```php
// Add test message to queue
$whatsappService->queueMessage(
    '6281234567890',
    'Test message',
    'high'
);

// Check queue status
$status = $whatsappService->getQueueStatus();
```

## Integration Points

### 1. Registration Controller
```php
// AuthController.php
public function register(Request $request)
{
    // ... user creation ...
    
    // Queue payment link message
    $whatsappService->queueMessage(
        $user->whatsapp_number,
        $paymentMessage,
        'high'
    );
}
```

### 2. Payment Webhook
```php
// XenditWebhookController.php
public function handlePayment(Request $request)
{
    // ... payment processing ...
    
    // Queue success notification
    $whatsappService->queueMessage(
        $user->whatsapp_number,
        $successMessage,
        'high'
    );
}
```

### 3. Admin Interface
```php
// Admin can monitor and control queue
Route::get('/admin/whatsapp-queue', [WhatsAppQueueController::class, 'index']);
```

## Best Practices

### 1. Message Prioritization
- **High Priority**: Payment links, urgent notifications
- **Normal Priority**: Registration confirmations, general messages
- **Low Priority**: Marketing messages, reminders

### 2. Error Handling
- Always implement retry logic
- Store failed messages for manual review
- Monitor queue health regularly

### 3. Performance Optimization
- Use Redis for queue storage in production
- Implement queue cleanup for old messages
- Monitor memory usage

## Troubleshooting

### 1. Queue Stuck
```php
// Force restart queue processing
$whatsappService->forceProcessQueue();
```

### 2. Too Many Failed Messages
```php
// Clear failed messages
$whatsappService->clearQueue();
```

### 3. Slow Processing
- Check WhatsApp API response times
- Verify network connectivity
- Monitor server resources

## Future Enhancements

### 1. Advanced Features
- Message templates
- Bulk message import
- Scheduled messaging
- Message analytics

### 2. Integration
- SMS fallback
- Email notifications
- Push notifications
- Multi-channel messaging

### 3. Monitoring
- Grafana dashboard
- Alert notifications
- Performance metrics
- Usage analytics
