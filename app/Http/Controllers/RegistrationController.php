<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Show registration form
     */
    public function index()
    {
        $categories = ['5K', '10K', '21K'];
        $ticketData = [];
        
        foreach ($categories as $category) {
            $currentTicket = TicketType::getCurrentTicketType($category);
            $allTickets = TicketType::getAvailableTicketTypes($category);
            
            $ticketData[$category] = [
                'current' => $currentTicket,
                'all' => $allTickets,
                'countdown' => $currentTicket ? $currentTicket->getTimeRemaining() : null
            ];
        }
        
        return view('register', compact('ticketData'));
    }

    /**
     * Get current ticket info via API
     */
    public function getTicketInfo(Request $request)
    {
        $category = $request->input('category');
        
        if (!in_array($category, ['5K', '10K', '21K'])) {
            return response()->json(['error' => 'Invalid category'], 400);
        }
        
        $currentTicket = TicketType::getCurrentTicketType($category);
        
        if (!$currentTicket) {
            return response()->json([
                'available' => false,
                'message' => 'Tiket sudah habis atau tidak tersedia untuk kategori ini'
            ]);
        }
        
        $timeRemaining = $currentTicket->getTimeRemaining();
        $remainingQuota = $currentTicket->getRemainingQuota();
        
        return response()->json([
            'available' => true,
            'ticket_type' => [
                'id' => $currentTicket->id,
                'name' => $currentTicket->name,
                'category' => $currentTicket->category, // Uses the accessor method
                'price' => $currentTicket->price,
                'formatted_price' => 'Rp ' . number_format($currentTicket->price, 0, ',', '.'),
                'quota' => $currentTicket->quota,
                'registered_count' => $currentTicket->registered_count,
                'remaining_quota' => $remainingQuota,
                'time_remaining' => $timeRemaining
            ]
        ]);
    }

    /**
     * Process registration
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations,email',
            'phone' => 'required|string|min:10|max:15',
            'category' => 'required|in:5K,10K,21K'
        ]);

        try {
            DB::beginTransaction();

            // Get current ticket type
            $ticketType = TicketType::getCurrentTicketType($request->category);
            
            if (!$ticketType) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tickets available for this category at the moment'
                ], 400);
            }

            // Check quota
            if ($ticketType->isQuotaExceeded()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quota for this ticket type has been exceeded'
                ], 400);
            }

            // Format phone number
            $phone = $this->formatPhoneNumber($request->phone);

            // Create registration
            $registration = Registration::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
                'category' => $request->category,
                'ticket_type_id' => $ticketType->id,
                'price' => $ticketType->price,
                'payment_status' => 'pending',
                'registration_number' => Registration::generateRegistrationNumber(),
                'whatsapp_verified_at' => Carbon::now(), // Auto-verify for now
                'is_active' => true
            ]);

            // Increment ticket type registered count
            $ticketType->incrementRegisteredCount();

            // Create Xendit payment (implement your Xendit logic here)
            $xenditResult = $this->createXenditPayment($registration);
            
            if (!$xenditResult['success']) {
                throw new \Exception('Failed to create payment: ' . $xenditResult['message']);
            }

            // Update registration with Xendit info
            $registration->update([
                'xendit_invoice_id' => $xenditResult['invoice_id'],
                'payment_method' => 'xendit'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'registration_number' => $registration->registration_number,
                    'ticket_type' => $ticketType->name,
                    'price' => $registration->formatted_price,
                    'payment_url' => $xenditResult['payment_url']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Registration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Create Xendit payment
     */
    private function createXenditPayment($registration)
    {
        // Implement your Xendit payment creation logic here
        // This is a placeholder - you'll need to implement the actual Xendit API calls
        
        try {
            // Mock implementation
            $invoiceId = 'INV-' . time() . '-' . $registration->id;
            $paymentUrl = 'https://checkout.xendit.co/' . $invoiceId;
            
            return [
                'success' => true,
                'invoice_id' => $invoiceId,
                'payment_url' => $paymentUrl
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle Xendit webhook
     */
    public function xenditWebhook(Request $request)
    {
        // Implement webhook handling logic
        $payload = $request->all();
        
        try {
            if ($payload['status'] === 'PAID') {
                $registration = Registration::where('xendit_invoice_id', $payload['external_id'])->first();
                
                if ($registration) {
                    $registration->update([
                        'payment_status' => 'paid',
                        'xendit_payment_id' => $payload['payment_id'] ?? null,
                        'paid_at' => Carbon::now()
                    ]);
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Xendit webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Get registration statistics
     */
    public function getStats()
    {
        $stats = [];
        $categories = ['5K', '10K', '21K'];
        
        foreach ($categories as $category) {
            $tickets = TicketType::where('category', $category)->get();
            $totalRegistrations = Registration::where('category', $category)->count();
            $paidRegistrations = Registration::where('category', $category)->where('payment_status', 'paid')->count();
            
            $stats[$category] = [
                'total_registrations' => $totalRegistrations,
                'paid_registrations' => $paidRegistrations,
                'tickets' => $tickets->map(function ($ticket) {
                    return [
                        'name' => $ticket->name,
                        'price' => $ticket->price,
                        'quota' => $ticket->quota,
                        'registered' => $ticket->registered_count,
                        'remaining' => $ticket->getRemainingQuota(),
                        'active' => $ticket->isCurrentlyActive()
                    ];
                })
            ];
        }
        
        return response()->json($stats);
    }
}
