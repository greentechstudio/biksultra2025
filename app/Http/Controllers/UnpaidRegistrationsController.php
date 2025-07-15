<?php

namespace App\Http\Controllers;

use App\Jobs\CleanupUnpaidRegistrations;
use App\Jobs\SendPaymentReminders;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnpaidRegistrationsController extends Controller
{
    /**
     * Get statistics about unpaid registrations
     */
    public function stats()
    {
        $totalUnpaid = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->count();
            
        $needReminders = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->count();
            
        $needCleanup = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->count();
            
        return response()->json([
            'total_unpaid' => $totalUnpaid,
            'need_reminders' => $needReminders,
            'need_cleanup' => $needCleanup,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Dry run - show what would be done without actually doing it
     */
    public function dryRun()
    {
        $reminderUsers = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->get();
            
        $cleanupUsers = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();
            
        $reminderCandidates = [];
        foreach ($reminderUsers as $user) {
            $minutesAgo = $user->created_at->diffInMinutes(now());
            if ($minutesAgo >= 30 && $minutesAgo % 30 === 0) {
                $reminderCandidates[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'minutes_ago' => $minutesAgo,
                    'hours_left' => 6 - $user->created_at->diffInHours(now())
                ];
            }
        }
        
        $cleanupCandidates = [];
        foreach ($cleanupUsers as $user) {
            $cleanupCandidates[] = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'hours_ago' => $user->created_at->diffInHours(now()),
                'registered_at' => $user->created_at->toISOString()
            ];
        }
        
        return response()->json([
            'dry_run' => true,
            'reminder_candidates' => $reminderCandidates,
            'cleanup_candidates' => $cleanupCandidates,
            'summary' => [
                'would_send_reminders' => count($reminderCandidates),
                'would_cleanup' => count($cleanupCandidates)
            ]
        ]);
    }
    
    /**
     * Send payment reminders
     */
    public function sendReminders()
    {
        SendPaymentReminders::dispatch();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment reminders job dispatched',
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Cleanup expired registrations
     */
    public function cleanup()
    {
        CleanupUnpaidRegistrations::dispatch();
        
        return response()->json([
            'success' => true,
            'message' => 'Cleanup job dispatched',
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Process all (reminders + cleanup)
     */
    public function processAll()
    {
        SendPaymentReminders::dispatch();
        CleanupUnpaidRegistrations::dispatch();
        
        return response()->json([
            'success' => true,
            'message' => 'Both reminder and cleanup jobs dispatched',
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Create a test user for testing
     */
    public function createTestUser(Request $request)
    {
        $hoursAgo = $request->input('hours_ago', 0.5);
        
        $user = User::create([
            'name' => 'Test User ' . Str::random(4),
            'email' => 'test' . Str::random(4) . '@example.com',
            'phone' => '+62812' . rand(10000000, 99999999),
            'password' => bcrypt('password'),
            'payment_status' => 'pending',
            'payment_date' => null,
            'created_at' => Carbon::now()->subHours($hoursAgo),
            'updated_at' => Carbon::now()->subHours($hoursAgo)
        ]);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'created_at' => $user->created_at->toISOString(),
                'hours_ago' => $hoursAgo
            ]
        ]);
    }
    
    /**
     * Create multiple test users
     */
    public function createTestBatch()
    {
        $users = [];
        
        // Create users at different intervals
        $intervals = [0.5, 1, 2, 3, 4, 5, 7, 8]; // hours ago
        
        foreach ($intervals as $hoursAgo) {
            $user = User::create([
                'name' => 'Test User ' . Str::random(4),
                'email' => 'test' . Str::random(4) . '@example.com',
                'phone' => '+62812' . rand(10000000, 99999999),
                'password' => bcrypt('password'),
                'payment_status' => 'pending',
                'payment_date' => null,
                'created_at' => Carbon::now()->subHours($hoursAgo),
                'updated_at' => Carbon::now()->subHours($hoursAgo)
            ]);
            
            $users[] = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'hours_ago' => $hoursAgo,
                'status' => $hoursAgo > 6 ? 'expired' : 'active'
            ];
        }
        
        return response()->json([
            'success' => true,
            'users' => $users,
            'total_created' => count($users)
        ]);
    }
    
    /**
     * Get all unpaid users
     */
    public function getUsers()
    {
        $users = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_date')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $userData = [];
        foreach ($users as $user) {
            $hoursAgo = $user->created_at->diffInHours(now());
            $minutesAgo = $user->created_at->diffInMinutes(now());
            
            $userData[] = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'created_at' => $user->created_at->toISOString(),
                'hours_ago' => $hoursAgo,
                'minutes_ago' => $minutesAgo,
                'status' => $hoursAgo > 6 ? 'expired' : 'active',
                'needs_reminder' => $minutesAgo >= 30 && $minutesAgo % 30 === 0,
                'time_left' => max(0, 6 - $hoursAgo)
            ];
        }
        
        return response()->json([
            'users' => $userData,
            'total' => count($userData)
        ]);
    }
    
    /**
     * Get recent logs
     */
    public function getLogs()
    {
        $logs = [
            'payment_reminders' => [],
            'cleanup' => [],
            'laravel' => []
        ];
        
        // Payment reminders log
        $reminderLogPath = storage_path('logs/payment-reminders.log');
        if (file_exists($reminderLogPath)) {
            $logs['payment_reminders'] = array_slice(file($reminderLogPath), -10);
        }
        
        // Cleanup log
        $cleanupLogPath = storage_path('logs/cleanup-unpaid.log');
        if (file_exists($cleanupLogPath)) {
            $logs['cleanup'] = array_slice(file($cleanupLogPath), -10);
        }
        
        // Laravel log (last 20 lines, filter for relevant entries)
        $laravelLogPath = storage_path('logs/laravel.log');
        if (file_exists($laravelLogPath)) {
            $allLines = file($laravelLogPath);
            $relevantLines = array_filter($allLines, function($line) {
                return stripos($line, 'unpaid') !== false || 
                       stripos($line, 'payment') !== false || 
                       stripos($line, 'cleanup') !== false;
            });
            $logs['laravel'] = array_slice($relevantLines, -10);
        }
        
        return response()->json($logs);
    }
    
    /**
     * Get job status
     */
    public function getJobs()
    {
        // This would require a queue monitoring package like Laravel Horizon
        // For now, just return basic info
        return response()->json([
            'message' => 'Queue jobs are processed in the background',
            'note' => 'Use "php artisan queue:work" to process jobs',
            'scheduled_jobs' => [
                'SendPaymentReminders' => 'Every 5 minutes',
                'CleanupUnpaidRegistrations' => 'Every 30 minutes'
            ]
        ]);
    }
}
