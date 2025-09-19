<?php

namespace App\Services;

use App\Models\User;
use App\Models\TicketType;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use App\Services\RandomPasswordService;

class IndividualRegistrationService
{
    protected $xenditService;
    protected $whatsappService;
    protected $randomPasswordService;

    public function __construct(
        XenditService $xenditService,
        WhatsAppService $whatsappService,
        RandomPasswordService $randomPasswordService
    ) {
        $this->xenditService = $xenditService;
        $this->whatsappService = $whatsappService;
        $this->randomPasswordService = $randomPasswordService;
    }

    /**
     * Process individual registration
     */
    public function processRegistration(Request $request): array
    {
        try {
            // Validate request data
            $validatedData = $this->validateRegistrationData($request);

            // Check if user exists
            $user = $this->findOrCreateUser($validatedData);

            // Validate ticket type and pricing
            $ticketType = $this->validateTicketType($validatedData['ticket_type_id']);

            // Create Xendit invoice
            $invoiceResponse = $this->createPaymentInvoice($user, $ticketType, $validatedData);

            // Update user data
            $this->updateUserData($user, $validatedData, $ticketType);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($user, $invoiceResponse);

            return [
                'success' => true,
                'user_id' => $user->id,
                'invoice_url' => $invoiceResponse['invoice_url'],
                'external_id' => $invoiceResponse['external_id']
            ];

        } catch (\Exception $e) {
            Log::error('Individual registration failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate registration data
     */
    protected function validateRegistrationData(Request $request): array
    {
        return $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'nama_bib' => 'required|string|max:255',
            'ukuran_jersey' => 'required|in:XS,S,M,L,XL,XXL',
            'kontak_darurat_nama' => 'required|string|max:255',
            'kontak_darurat_hubungan' => 'required|string|max:255',
            'kontak_darurat_no_wa' => 'required|string|max:20',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'g-recaptcha-response' => 'required'
        ]);
    }

    /**
     * Find existing user or create new one
     */
    protected function findOrCreateUser(array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            $password = $this->randomPasswordService->generate();
            
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'no_wa' => $data['no_wa'],
                'plain_password' => $password
            ]);
        }

        return $user;
    }

    /**
     * Validate ticket type and check availability
     */
    protected function validateTicketType(int $ticketTypeId): TicketType
    {
        $ticketType = TicketType::with('raceCategory')->findOrFail($ticketTypeId);
        
        if (!$ticketType->is_active) {
            throw new \Exception('Ticket type is not active');
        }

        if ($ticketType->quota && $ticketType->registered_count >= $ticketType->quota) {
            throw new \Exception('Ticket type is sold out');
        }

        $now = now();
        if ($now < $ticketType->start_date || $now > $ticketType->end_date) {
            throw new \Exception('Ticket type is not available at this time');
        }

        return $ticketType;
    }

    /**
     * Create payment invoice via Xendit
     */
    protected function createPaymentInvoice(User $user, TicketType $ticketType, array $data): array
    {
        return $this->xenditService->createInvoice(
            $user,
            $ticketType->price,
            "Pendaftaran {$ticketType->raceCategory->name} - {$ticketType->name}",
            'individual'
        );
    }

    /**
     * Update user registration data
     */
    protected function updateUserData(User $user, array $data, TicketType $ticketType): void
    {
        $user->update([
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'alamat' => $data['alamat'],
            'nama_bib' => $data['nama_bib'],
            'ukuran_jersey' => $data['ukuran_jersey'],
            'kontak_darurat_nama' => $data['kontak_darurat_nama'],
            'kontak_darurat_hubungan' => $data['kontak_darurat_hubungan'],
            'kontak_darurat_no_wa' => $data['kontak_darurat_no_wa'],
            'ticket_type_id' => $ticketType->id,
            'race_category_id' => $ticketType->race_category_id,
            'total_price' => $ticketType->price
        ]);
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification(User $user, array $invoiceData): void
    {
        try {
            $message = "Halo {$user->name}! Pendaftaran Anda berhasil. Silakan lakukan pembayaran melalui link berikut: {$invoiceData['invoice_url']}";
            
            $this->whatsappService->sendMessage($user->no_wa, $message);
        } catch (\Exception $e) {
            Log::warning('WhatsApp notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}