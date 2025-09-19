<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Models\TicketType;
use App\Services\Registration\CollectiveRegistrationService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CollectiveRegistrationController extends Controller
{
    private $registrationService;
    private $recaptchaService;

    public function __construct(
        CollectiveRegistrationService $registrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->registrationService = $registrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Show the collective registration form
     */
    public function showRegisterKolektif()
    {
        try {
            $jerseySizes = JerseySize::select('id', 'name')->where('active', 1)->get();
            $raceCategories = RaceCategory::select('id', 'name')->where('active', 1)->get();
            $bloodTypes = BloodType::select('id', 'name')->where('active', 1)->get();
            $eventSources = EventSource::select('id', 'name')->where('active', 1)->get();
            $ticketTypes = TicketType::select('id', 'name', 'price')->where('active', 1)->get();

            Log::info('Collective registration form data loaded', [
                'jerseySizes_count' => $jerseySizes->count(),
                'raceCategories_count' => $raceCategories->count(),
                'bloodTypes_count' => $bloodTypes->count(),
                'eventSources_count' => $eventSources->count(),
                'ticketTypes_count' => $ticketTypes->count()
            ]);

            return view('auth.register-kolektif', compact(
                'jerseySizes', 
                'raceCategories', 
                'bloodTypes', 
                'eventSources',
                'ticketTypes'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading collective registration form data: ' . $e->getMessage());
            
            // Fallback to empty arrays
            return view('auth.register-kolektif', [
                'jerseySizes' => [],
                'raceCategories' => [],
                'bloodTypes' => [],
                'eventSources' => [],
                'ticketTypes' => []
            ]);
        }
    }

    /**
     * Handle collective registration
     */
    public function registerKolektif(Request $request)
    {
        // Validate reCAPTCHA
        $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
        if (!$recaptchaResult['success']) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Validate participants data
        $participants = $request->input('participants', []);
        
        if (empty($participants) || count($participants) < 2) {
            return back()
                ->withErrors(['participants' => 'Collective registration requires at least 2 participants.'])
                ->withInput();
        }

        if (count($participants) > 50) {
            return back()
                ->withErrors(['participants' => 'Maximum 50 participants allowed per collective registration.'])
                ->withInput();
        }

        // Validate each participant
        $validatedParticipants = [];
        foreach ($participants as $index => $participant) {
            $validator = $this->getParticipantValidator($participant, $index);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator->errors()->all())
                    ->withInput();
            }
            
            $validatedParticipants[] = $validator->validated();
        }

        // Check ticket availability for all participants
        $ticketTypeCounts = [];
        foreach ($validatedParticipants as $participant) {
            $ticketTypeId = $participant['ticket_type_id'];
            $ticketTypeCounts[$ticketTypeId] = ($ticketTypeCounts[$ticketTypeId] ?? 0) + 1;
        }

        foreach ($ticketTypeCounts as $ticketTypeId => $count) {
            $ticketType = TicketType::findOrFail($ticketTypeId);
            if (($ticketType->registered_count + $count) > $ticketType->quota) {
                return back()
                    ->withErrors(['quota' => "Not enough quota for ticket type: {$ticketType->name}. Available: " . ($ticketType->quota - $ticketType->registered_count) . ", Requested: {$count}"])
                    ->withInput();
            }
        }

        // Process collective registration
        $result = $this->registrationService->registerCollectiveGroup($validatedParticipants);

        if ($result['success']) {
            Log::info('Collective registration successful', [
                'primary_user_id' => $result['primary_user']->id,
                'participant_count' => count($result['users']),
                'total_amount' => $result['total_amount']
            ]);

            return redirect()->route('collective.success')
                ->with('success', 'Collective registration successful!')
                ->with('primary_user', $result['primary_user'])
                ->with('participants', $result['users'])
                ->with('invoice_url', $result['invoice_url'])
                ->with('total_amount', $result['total_amount']);
        } else {
            return back()
                ->withErrors(['registration' => $result['message']])
                ->withInput();
        }
    }

    /**
     * Show collective registration success page
     */
    public function collectiveSuccess()
    {
        if (!session()->has('primary_user')) {
            return redirect()->route('home')
                ->withErrors(['access' => 'Invalid access to success page.']);
        }

        $primaryUser = session('primary_user');
        $participants = session('participants', []);
        $invoiceUrl = session('invoice_url');
        $totalAmount = session('total_amount');

        return view('auth.collective-success', compact(
            'primaryUser', 
            'participants', 
            'invoiceUrl', 
            'totalAmount'
        ));
    }

    /**
     * Get participant validator
     */
    private function getParticipantValidator(array $data, int $index): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'blood_type_id' => 'required|exists:blood_types,id',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'race_category_id' => 'required|exists:race_categories,id',
            'jersey_size_id' => 'required|exists:jersey_sizes,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'event_source_id' => 'nullable|exists:event_sources,id',
        ];

        // Add prefix to field names for clearer error messages
        $prefixedData = [];
        foreach ($data as $key => $value) {
            $prefixedData["participant_{$index}_{$key}"] = $value;
        }

        $prefixedRules = [];
        foreach ($rules as $key => $rule) {
            $prefixedRules["participant_{$index}_{$key}"] = str_replace(['required', 'unique:users'], ["required", "unique:users,{$key}"], $rule);
        }

        $validator = Validator::make($prefixedData, $prefixedRules);

        // Custom error messages
        $messages = [];
        foreach ($rules as $key => $rule) {
            $messages["participant_{$index}_{$key}.required"] = "Participant " . ($index + 1) . " {$key} is required.";
            $messages["participant_{$index}_{$key}.unique"] = "Participant " . ($index + 1) . " {$key} already exists.";
            $messages["participant_{$index}_{$key}.email"] = "Participant " . ($index + 1) . " email must be valid.";
            $messages["participant_{$index}_{$key}.exists"] = "Participant " . ($index + 1) . " {$key} is invalid.";
        }

        $validator->setCustomMessages($messages);

        // If validation passes, return original data format
        if (!$validator->fails()) {
            return Validator::make($data, $rules);
        }

        return $validator;
    }
}