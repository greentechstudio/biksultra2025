<?php

namespace App\Services;

use App\Models\User;
use App\Models\TicketType;
use App\Models\RaceCategory;
use App\Models\CollectiveRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use App\Services\RandomPasswordService;

class CollectiveRegistrationService
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
     * Process collective registration
     */
    public function processRegistration(Request $request): array
    {
        DB::beginTransaction();
        
        try {
            // Validate request data
            $validatedData = $this->validateRegistrationData($request);

            // Find or create primary user
            $primaryUser = $this->findOrCreatePrimaryUser($validatedData);

            // Validate ticket type
            $ticketType = $this->validateTicketType($validatedData['ticket_type_id']);

            // Calculate total price
            $totalParticipants = count($validatedData['participants']);
            $totalPrice = $ticketType->price * $totalParticipants;

            // Create collective registration record
            $collectiveRegistration = $this->createCollectiveRegistration($primaryUser, $ticketType, $totalParticipants, $totalPrice);

            // Process participants
            $participants = $this->processParticipants($validatedData['participants'], $collectiveRegistration, $ticketType);

            // Create Xendit invoice
            $invoiceResponse = $this->createPaymentInvoice($primaryUser, $totalPrice, $ticketType, $totalParticipants);

            // Update collective registration with external ID
            $collectiveRegistration->update(['xendit_external_id' => $invoiceResponse['external_id']]);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($primaryUser, $invoiceResponse, $totalParticipants);

            DB::commit();

            return [
                'success' => true,
                'collective_id' => $collectiveRegistration->id,
                'primary_user_id' => $primaryUser->id,
                'participants_count' => $totalParticipants,
                'total_price' => $totalPrice,
                'invoice_url' => $invoiceResponse['invoice_url'],
                'external_id' => $invoiceResponse['external_id']
            ];

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Collective registration failed', [
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
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'participants' => 'required|array|min:1',
            'participants.*.nama_lengkap' => 'required|string|max:255',
            'participants.*.email' => 'required|email|max:255',
            'participants.*.no_wa' => 'required|string|max:20',
            'participants.*.tanggal_lahir' => 'required|date',
            'participants.*.jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'participants.*.alamat' => 'required|string',
            'participants.*.nama_bib' => 'required|string|max:255',
            'participants.*.ukuran_jersey' => 'required|in:XS,S,M,L,XL,XXL',
            'participants.*.kontak_darurat_nama' => 'required|string|max:255',
            'participants.*.kontak_darurat_hubungan' => 'required|string|max:255',
            'participants.*.kontak_darurat_no_wa' => 'required|string|max:20',
            'g-recaptcha-response' => 'required'
        ]);
    }

    /**
     * Find existing user or create new one
     */
    protected function findOrCreatePrimaryUser(array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            $password = $this->randomPasswordService->generate();
            
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'no_wa' => $data['no_wa'],
                'plain_password' => $password,
                'registration_type' => 'collective_primary'
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

        $now = now();
        if ($now < $ticketType->start_date || $now > $ticketType->end_date) {
            throw new \Exception('Ticket type is not available at this time');
        }

        return $ticketType;
    }

    /**
     * Create collective registration record
     */
    protected function createCollectiveRegistration(User $primaryUser, TicketType $ticketType, int $participantCount, float $totalPrice): CollectiveRegistration
    {
        return CollectiveRegistration::create([
            'primary_user_id' => $primaryUser->id,
            'ticket_type_id' => $ticketType->id,
            'race_category_id' => $ticketType->race_category_id,
            'participant_count' => $participantCount,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);
    }

    /**
     * Process all participants
     */
    protected function processParticipants(array $participantsData, CollectiveRegistration $collectiveRegistration, TicketType $ticketType): array
    {
        $participants = [];

        foreach ($participantsData as $participantData) {
            $participant = $this->findOrCreateParticipant($participantData);
            
            $this->updateParticipantData($participant, $participantData, $ticketType, $collectiveRegistration);
            
            $participants[] = $participant;
        }

        return $participants;
    }

    /**
     * Find or create participant user
     */
    protected function findOrCreateParticipant(array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            $password = $this->randomPasswordService->generate();
            
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'no_wa' => $data['no_wa'],
                'plain_password' => $password,
                'registration_type' => 'collective_participant'
            ]);
        }

        return $user;
    }

    /**
     * Update participant registration data
     */
    protected function updateParticipantData(User $participant, array $data, TicketType $ticketType, CollectiveRegistration $collectiveRegistration): void
    {
        $participant->update([
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
            'collective_registration_id' => $collectiveRegistration->id,
            'total_price' => $ticketType->price
        ]);
    }

    /**
     * Create payment invoice via Xendit
     */
    protected function createPaymentInvoice(User $primaryUser, float $totalPrice, TicketType $ticketType, int $participantCount): array
    {
        return $this->xenditService->createInvoice(
            $primaryUser,
            $totalPrice,
            "Pendaftaran Kolektif {$ticketType->raceCategory->name} - {$ticketType->name} ({$participantCount} peserta)",
            'collective'
        );
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification(User $primaryUser, array $invoiceData, int $participantCount): void
    {
        try {
            $message = "Halo {$primaryUser->name}! Pendaftaran kolektif untuk {$participantCount} peserta berhasil. Silakan lakukan pembayaran melalui link berikut: {$invoiceData['invoice_url']}";
            
            $this->whatsappService->sendMessage($primaryUser->no_wa, $message);
        } catch (\Exception $e) {
            Log::warning('WhatsApp notification failed', [
                'user_id' => $primaryUser->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}