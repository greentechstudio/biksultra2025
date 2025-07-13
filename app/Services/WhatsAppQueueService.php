<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendWhatsAppMessage;

class WhatsAppQueueService
{
    const QUEUE_KEY = 'whatsapp_message_queue';
    const DELAY_SECONDS = 10; // 10 detik delay antar pesan
    const PROCESSING_KEY = 'whatsapp_queue_processing';

    /**
     * Add message to queue
     */
    public function addToQueue($phoneNumber, $message, $priority = 'normal')
    {
        $queueItem = [
            'id' => uniqid('wa_', true),
            'phone_number' => $phoneNumber,
            'message' => $message,
            'priority' => $priority, // high, normal, low
            'created_at' => now()->timestamp,
            'attempts' => 0,
            'max_attempts' => 3,
            'status' => 'pending'
        ];

        // Get current queue
        $queue = Cache::get(self::QUEUE_KEY, []);
        
        // Add to queue based on priority
        if ($priority === 'high') {
            array_unshift($queue, $queueItem);
        } else {
            $queue[] = $queueItem;
        }

        // Save queue
        Cache::put(self::QUEUE_KEY, $queue, now()->addDay());

        Log::info('WhatsApp message added to queue', [
            'queue_id' => $queueItem['id'],
            'phone_number' => $phoneNumber,
            'priority' => $priority,
            'queue_length' => count($queue)
        ]);

        // Start processing if not already running
        $this->startProcessing();

        return $queueItem['id'];
    }

    /**
     * Start queue processing
     */
    public function startProcessing()
    {
        // Check if already processing
        if (Cache::has(self::PROCESSING_KEY)) {
            return false;
        }

        // Set processing flag
        Cache::put(self::PROCESSING_KEY, true, now()->addMinutes(30));

        // Dispatch job to process queue
        Queue::push(new SendWhatsAppMessage());

        return true;
    }

    /**
     * Process queue
     */
    public function processQueue()
    {
        while (true) {
            // Get queue
            $queue = Cache::get(self::QUEUE_KEY, []);
            
            if (empty($queue)) {
                // No messages to process
                Log::info('WhatsApp queue is empty, stopping processing');
                break;
            }

            // Get first message
            $queueItem = array_shift($queue);
            
            // Update queue
            Cache::put(self::QUEUE_KEY, $queue, now()->addDay());

            // Process message
            $this->processMessage($queueItem);

            // Wait 10 seconds before next message
            if (!empty($queue)) {
                Log::info('WhatsApp queue waiting 10 seconds', [
                    'remaining_messages' => count($queue)
                ]);
                sleep(self::DELAY_SECONDS);
            }
        }

        // Clear processing flag
        Cache::forget(self::PROCESSING_KEY);
        
        Log::info('WhatsApp queue processing completed');
    }

    /**
     * Process individual message
     */
    private function processMessage($queueItem)
    {
        try {
            Log::info('Processing WhatsApp message', [
                'queue_id' => $queueItem['id'],
                'phone_number' => $queueItem['phone_number'],
                'attempt' => $queueItem['attempts'] + 1
            ]);

            // Create WhatsApp service instance
            $whatsappService = app(WhatsAppService::class);
            
            // Send message
            $result = $whatsappService->sendMessage(
                $queueItem['phone_number'], 
                $queueItem['message']
            );

            if ($result) {
                Log::info('WhatsApp message sent successfully', [
                    'queue_id' => $queueItem['id'],
                    'phone_number' => $queueItem['phone_number']
                ]);
            } else {
                // Failed to send, retry if attempts < max_attempts
                $this->handleFailedMessage($queueItem);
            }

        } catch (\Exception $e) {
            Log::error('Exception processing WhatsApp message', [
                'queue_id' => $queueItem['id'],
                'phone_number' => $queueItem['phone_number'],
                'error' => $e->getMessage()
            ]);

            // Handle failed message
            $this->handleFailedMessage($queueItem);
        }
    }

    /**
     * Handle failed message
     */
    private function handleFailedMessage($queueItem)
    {
        $queueItem['attempts']++;
        
        if ($queueItem['attempts'] < $queueItem['max_attempts']) {
            // Retry - add back to queue with lower priority
            $queue = Cache::get(self::QUEUE_KEY, []);
            $queue[] = $queueItem;
            Cache::put(self::QUEUE_KEY, $queue, now()->addDay());
            
            Log::info('WhatsApp message queued for retry', [
                'queue_id' => $queueItem['id'],
                'phone_number' => $queueItem['phone_number'],
                'attempts' => $queueItem['attempts']
            ]);
        } else {
            // Max attempts reached, mark as failed
            Log::error('WhatsApp message failed after max attempts', [
                'queue_id' => $queueItem['id'],
                'phone_number' => $queueItem['phone_number'],
                'attempts' => $queueItem['attempts']
            ]);
            
            // Store failed message for manual review
            $this->storeFailedMessage($queueItem);
        }
    }

    /**
     * Store failed message for manual review
     */
    private function storeFailedMessage($queueItem)
    {
        $failedMessages = Cache::get('whatsapp_failed_messages', []);
        $failedMessages[] = [
            'queue_id' => $queueItem['id'],
            'phone_number' => $queueItem['phone_number'],
            'message' => $queueItem['message'],
            'attempts' => $queueItem['attempts'],
            'failed_at' => now()->timestamp
        ];
        
        Cache::put('whatsapp_failed_messages', $failedMessages, now()->addDays(7));
    }

    /**
     * Get queue status
     */
    public function getQueueStatus()
    {
        $queue = Cache::get(self::QUEUE_KEY, []);
        $isProcessing = Cache::has(self::PROCESSING_KEY);
        $failedMessages = Cache::get('whatsapp_failed_messages', []);

        return [
            'queue_length' => count($queue),
            'is_processing' => $isProcessing,
            'failed_messages' => count($failedMessages),
            'estimated_time' => count($queue) * self::DELAY_SECONDS,
            'next_messages' => array_slice($queue, 0, 5) // Show next 5 messages
        ];
    }

    /**
     * Get queue statistics
     */
    public function getQueueStats()
    {
        $queue = Cache::get(self::QUEUE_KEY, []);
        
        $stats = [
            'total' => count($queue),
            'pending' => 0,
            'processing' => 0,
            'failed' => 0,
            'completed' => 0
        ];
        
        foreach ($queue as $item) {
            $stats[$item['status']]++;
        }
        
        return $stats;
    }

    /**
     * Clear queue (emergency use)
     */
    public function clearQueue()
    {
        Cache::forget(self::QUEUE_KEY);
        Cache::forget(self::PROCESSING_KEY);
        Cache::forget('whatsapp_failed_messages');
        
        Log::warning('WhatsApp queue cleared manually');
        
        return true;
    }

    /**
     * Force process queue (restart if stuck)
     */
    public function forceProcessQueue()
    {
        Cache::forget(self::PROCESSING_KEY);
        return $this->startProcessing();
    }
}
