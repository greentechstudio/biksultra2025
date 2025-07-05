<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job should run.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Starting payment reminder job');
        
        $now = Carbon::now();
        
        // Find users who need reminders (every 30 minutes for first 6 hours)
        $usersNeedingReminders = User::where('payment_status', '!=', 'paid')
            ->whereNull('payment_confirmed_at')
            ->where('created_at', '>', $now->copy()->subHours(6)) // Still within 6 hour window
            ->get();

        $reminderCount = 0;
        
        foreach ($usersNeedingReminders as $user) {
            $hoursElapsed = $user->created_at->diffInHours($now);
            $minutesElapsed = $user->created_at->diffInMinutes($now);
            
            // Send reminders every 30 minutes (at 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360 minutes)
            $reminderIntervals = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360];
            
            foreach ($reminderIntervals as $interval) {
                // Check if current time matches reminder interval (within 5 minute window)
                if ($minutesElapsed >= $interval && $minutesElapsed < ($interval + 5)) {
                    // Check if reminder was already sent for this interval
                    $lastReminderKey = "payment_reminder_{$user->id}_{$interval}";
                    if (!\Cache::has($lastReminderKey)) {
                        $this->sendPaymentReminder($user, $interval);
                        $reminderCount++;
                        
                        // Mark reminder as sent for this interval
                        \Cache::put($lastReminderKey, true, $now->addDay());
                        break; // Only send one reminder per cycle
                    }
                }
            }
        }
        
        Log::info('Payment reminders completed', [
            'reminders_sent' => $reminderCount
        ]);
    }

    /**
     * Send payment reminder to user
     */
    private function sendPaymentReminder($user, $minutesElapsed)
    {
        $whatsappService = app(WhatsAppService::class);
        
        $hoursElapsed = floor($minutesElapsed / 60);
        $remainingMinutes = $minutesElapsed % 60;
        $timeElapsed = $hoursElapsed > 0 ? "{$hoursElapsed} jam {$remainingMinutes} menit" : "{$remainingMinutes} menit";
        
        $hoursRemaining = 6 - ceil($minutesElapsed / 60);
        $minutesRemainingTotal = (6 * 60) - $minutesElapsed;
        $hoursRem = floor($minutesRemainingTotal / 60);
        $minsRem = $minutesRemainingTotal % 60;
        $timeRemaining = $hoursRem > 0 ? "{$hoursRem} jam {$minsRem} menit" : "{$minsRem} menit";
        
        $registrationTime = $user->created_at->format('d/m/Y H:i');
        $amount = $user->registration_fee ? number_format($user->registration_fee, 0, ',', '.') : 'Sesuai kategori';
        
        $message = "⏰ *PENGINGAT PEMBAYARAN* ⏰\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Ini adalah pengingat untuk menyelesaikan pembayaran registrasi Amazing Sultra Run Anda.\n\n";
        $message .= "📋 *Detail Registrasi:*\n";
        $message .= "• Nama: {$user->name}\n";
        $message .= "• Kategori: {$user->race_category}\n";
        $message .= "• Biaya: Rp {$amount}\n";
        $message .= "• Waktu Registrasi: {$registrationTime}\n";
        $message .= "• Waktu Berlalu: {$timeElapsed}\n";
        $message .= "• Sisa Waktu: {$timeRemaining}\n\n";
        
        if ($user->payment_url) {
            $message .= "💳 *Link Pembayaran:*\n";
            $message .= "{$user->payment_url}\n\n";
        }
        
        $message .= "⚠️ *PENTING:*\n";
        $message .= "• Pembayaran harus diselesaikan dalam 6 jam setelah registrasi\n";
        $message .= "• Data akan dihapus otomatis jika tidak ada pembayaran\n";
        $message .= "• Anda akan menerima pengingat setiap 30 menit\n\n";
        
        if ($minutesElapsed >= 300) { // 5 hours (300 minutes)
            $message .= "🚨 *PERINGATAN TERAKHIR:*\n";
            $message .= "Waktu pembayaran hampir habis! Segera lakukan pembayaran untuk menghindari penghapusan data.\n\n";
        }
        
        $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
        $message .= "Terima kasih! 🙏\n\n";
        $message .= "_Pengingat otomatis #{" . ceil($minutesElapsed / 30) . "} - Amazing Sultra Run_";

        $priority = $minutesElapsed >= 300 ? 'high' : 'normal'; // High priority for last hour
        
        $result = $whatsappService->queueMessage($user->whatsapp_number, $message, $priority);
        
        Log::info('Payment reminder sent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'minutes_elapsed' => $minutesElapsed,
            'reminder_number' => ceil($minutesElapsed / 30),
            'priority' => $priority
        ]);
        
        return $result;
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Send payment reminders job failed', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
