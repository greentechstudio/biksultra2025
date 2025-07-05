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

class CleanupUnpaidRegistrations implements ShouldQueue
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
        Log::info('Starting cleanup of unpaid registrations');
        
        $cutoffTime = Carbon::now()->subHours(6);
        
        // Find users who registered more than 6 hours ago and haven't paid
        $unpaidUsers = User::where('created_at', '<', $cutoffTime)
            ->where('payment_status', '!=', 'paid')
            ->whereNull('payment_confirmed_at')
            ->get();

        $deletedCount = 0;
        $notificationCount = 0;
        
        foreach ($unpaidUsers as $user) {
            try {
                // Send deletion notification
                $this->sendDeletionNotification($user);
                $notificationCount++;
                
                // Delete user
                $user->delete();
                $deletedCount++;
                
                Log::info('Deleted unpaid user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'registered_at' => $user->created_at,
                    'hours_since_registration' => $user->created_at->diffInHours(Carbon::now())
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error deleting unpaid user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info('Cleanup completed', [
            'users_deleted' => $deletedCount,
            'notifications_sent' => $notificationCount,
            'cutoff_time' => $cutoffTime
        ]);
    }

    /**
     * Send deletion notification to user
     */
    private function sendDeletionNotification($user)
    {
        $whatsappService = app(WhatsAppService::class);
        
        $registrationTime = $user->created_at->format('d/m/Y H:i');
        $hoursElapsed = $user->created_at->diffInHours(Carbon::now());
        
        $message = "⚠️ *PEMBERITAHUAN PENGHAPUSAN DATA* ⚠️\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Data registrasi Anda di Amazing Sultra Run telah dihapus karena tidak melakukan pembayaran dalam 6 jam.\n\n";
        $message .= "📋 *Detail Registrasi:*\n";
        $message .= "• Nama: {$user->name}\n";
        $message .= "• Email: {$user->email}\n";
        $message .= "• Kategori: {$user->race_category}\n";
        $message .= "• Waktu Registrasi: {$registrationTime}\n";
        $message .= "• Waktu Berlalu: {$hoursElapsed} jam\n\n";
        $message .= "🔄 *Registrasi Ulang:*\n";
        $message .= "Jika Anda masih ingin mengikuti event ini, silakan lakukan registrasi ulang di:\n";
        $message .= url('/register') . "\n\n";
        $message .= "⏰ *Reminder:*\n";
        $message .= "• Pembayaran harus dilakukan dalam 6 jam setelah registrasi\n";
        $message .= "• Data akan dihapus otomatis jika tidak ada pembayaran\n";
        $message .= "• Lakukan pembayaran segera setelah registrasi\n\n";
        $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
        $message .= "Terima kasih atas pengertian Anda.\n\n";
        $message .= "_Pesan otomatis - Amazing Sultra Run_";

        return $whatsappService->queueMessage($user->whatsapp_number, $message, 'high');
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Cleanup unpaid registrations job failed', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
