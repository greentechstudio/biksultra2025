<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use App\Services\WhatsAppQueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppQueueController extends Controller
{
    private $whatsappService;
    private $queueService;

    public function __construct(WhatsAppService $whatsappService, WhatsAppQueueService $queueService)
    {
        $this->whatsappService = $whatsappService;
        $this->queueService = $queueService;
    }

    /**
     * Show queue status
     */
    public function index()
    {
        $status = $this->whatsappService->getQueueStatus();
        
        return view('admin.whatsapp-queue', compact('status'));
    }

    /**
     * Get queue status (API)
     */
    public function status()
    {
        $status = $this->whatsappService->getQueueStatus();
        
        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Clear queue
     */
    public function clear()
    {
        $result = $this->whatsappService->clearQueue();
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Queue cleared successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear queue'
            ]);
        }
    }

    /**
     * Force process queue
     */
    public function forceProcess()
    {
        $result = $this->whatsappService->forceProcessQueue();
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Queue processing started'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Queue is already processing'
            ]);
        }
    }

    /**
     * Send test message
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
            'priority' => 'nullable|in:high,normal,low'
        ]);

        $queueId = $this->whatsappService->queueMessage(
            $request->phone_number,
            $request->message,
            $request->priority ?? 'normal'
        );

        return response()->json([
            'success' => true,
            'message' => 'Test message queued successfully',
            'queue_id' => $queueId
        ]);
    }

    /**
     * Get unpaid registrations status
     */
    public function unpaidStatus()
    {
        $now = \Carbon\Carbon::now();
        
        // Users registered but not paid
        $unpaidUsers = \App\Models\User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_confirmed_at')
            ->get();
        
        $stats = [
            'total_unpaid' => $unpaidUsers->count(),
            'within_6_hours' => 0,
            'over_6_hours' => 0,
            'next_cleanup_time' => null,
            'users_by_time_remaining' => []
        ];
        
        foreach ($unpaidUsers as $user) {
            $hoursElapsed = $user->created_at->diffInHours($now);
            $minutesElapsed = $user->created_at->diffInMinutes($now);
            $timeRemaining = (6 * 60) - $minutesElapsed;
            
            if ($hoursElapsed < 6) {
                $stats['within_6_hours']++;
                
                $hoursRem = floor($timeRemaining / 60);
                $minsRem = $timeRemaining % 60;
                $timeRemainingStr = $hoursRem > 0 ? "{$hoursRem}h {$minsRem}m" : "{$minsRem}m";
                
                $stats['users_by_time_remaining'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->whatsapp_number,
                    'category' => $user->race_category,
                    'registered_at' => $user->created_at->format('d/m/Y H:i'),
                    'hours_elapsed' => round($hoursElapsed, 1),
                    'minutes_elapsed' => $minutesElapsed,
                    'time_remaining' => $timeRemainingStr,
                    'next_reminder_in' => $this->getNextReminderTime($minutesElapsed),
                    'reminders_sent' => ceil($minutesElapsed / 30)
                ];
            } else {
                $stats['over_6_hours']++;
            }
        }
        
        // Sort by time remaining (ascending)
        usort($stats['users_by_time_remaining'], function($a, $b) {
            return $a['minutes_elapsed'] <=> $b['minutes_elapsed'];
        });
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    /**
     * Force cleanup unpaid registrations
     */
    public function forceCleanup()
    {
        \App\Jobs\CleanupUnpaidRegistrations::dispatch();
        
        return response()->json([
            'success' => true,
            'message' => 'Cleanup job dispatched'
        ]);
    }
    
    /**
     * Force send payment reminders
     */
    public function forceReminders()
    {
        \App\Jobs\SendPaymentReminders::dispatch();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment reminders job dispatched'
        ]);
    }
    
    /**
     * Get next reminder time for a user
     */
    private function getNextReminderTime($minutesElapsed)
    {
        $reminderIntervals = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360];
        
        foreach ($reminderIntervals as $interval) {
            if ($minutesElapsed < $interval) {
                $minutesUntilNext = $interval - $minutesElapsed;
                return $minutesUntilNext . ' minutes';
            }
        }
        
        return 'No more reminders';
    }
}
