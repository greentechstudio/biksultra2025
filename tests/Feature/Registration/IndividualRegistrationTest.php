<?php

namespace Tests\Feature\Registration;

use Tests\TestCase;
use App\Models\User;
use App\Models\TicketType;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class IndividualRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        Config::set('xendit.secret_key', 'test_secret_key');
        Config::set('xendit.base_url', 'https://api.xendit.co');
        Config::set('xendit.webhook_token', 'test_webhook_token');
        Config::set('recaptcha.secret_key', 'test_recaptcha_key');
        
        // Create necessary test data
        $this->createTestData();
    }

    /** @test */
    public function user_can_view_individual_registration_form()
    {
        $response = $this->get('/nightrun');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
        $response->assertViewHas(['jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources', 'ticketTypes']);
    }

    /** @test */
    public function user_can_register_with_manual_password()
    {
        // Mock external services
        $this->mockExternalServices();

        $registrationData = $this->getValidRegistrationData();

        $response = $this->post('/nightrun', $registrationData);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'status' => 'registered'
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->xendit_external_id);
        $this->assertNotNull($user->xendit_invoice_id);
        $this->assertNotNull($user->xendit_invoice_url);
    }

    /** @test */
    public function user_can_view_random_password_registration_form()
    {
        $response = $this->get('/register-random-password');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register-random-password');
        $response->assertViewHas(['jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources', 'ticketTypes']);
    }

    /** @test */
    public function user_can_register_with_random_password()
    {
        // Mock external services
        $this->mockExternalServices();

        $registrationData = $this->getValidRegistrationData();
        unset($registrationData['password']);
        unset($registrationData['password_confirmation']);

        $response = $this->post('/register-random-password', $registrationData);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'status' => 'registered'
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->xendit_external_id);
    }

    /** @test */
    public function registration_fails_with_invalid_recaptcha()
    {
        $registrationData = $this->getValidRegistrationData();
        $registrationData['g-recaptcha-response'] = 'invalid_recaptcha';

        // Mock failed reCAPTCHA response
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response']
            ], 200)
        ]);

        $response = $this->post('/register', $registrationData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('recaptcha');
        
        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com'
        ]);
    }

    /** @test */
    public function registration_fails_when_ticket_quota_is_full()
    {
        $ticketType = TicketType::first();
        $ticketType->update([
            'quota' => 5,
            'registered_count' => 5
        ]);

        $registrationData = $this->getValidRegistrationData();
        $registrationData['ticket_type_id'] = $ticketType->id;

        $response = $this->post('/register', $registrationData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('ticket_type_id');
        
        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com'
        ]);
    }

    /** @test */
    public function registration_fails_with_duplicate_email()
    {
        // Create existing user
        User::factory()->create(['email' => 'john@example.com']);

        $registrationData = $this->getValidRegistrationData();

        $response = $this->post('/register', $registrationData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Required field missing
            'email' => 'invalid-email', // Invalid email format
            'whatsapp_number' => '',
            'password' => '123', // Too short
            'password_confirmation' => '456', // Doesn't match
        ];

        $response = $this->post('/register', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'email', 'whatsapp_number', 'password']);
        
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email'
        ]);
    }

    /** @test */
    public function user_is_automatically_logged_in_after_registration()
    {
        $this->mockExternalServices();

        $registrationData = $this->getValidRegistrationData();

        $response = $this->post('/register', $registrationData);

        $this->assertAuthenticated();
        
        $user = User::where('email', 'john@example.com')->first();
        $this->assertEquals($user->id, auth()->id());
    }

    /** @test */
    public function ticket_type_registered_count_is_incremented()
    {
        $this->mockExternalServices();

        $ticketType = TicketType::first();
        $initialCount = $ticketType->registered_count;

        $registrationData = $this->getValidRegistrationData();
        $registrationData['ticket_type_id'] = $ticketType->id;

        $this->post('/register', $registrationData);

        $ticketType->refresh();
        $this->assertEquals($initialCount + 1, $ticketType->registered_count);
    }

    /** @test */
    public function registration_creates_unique_external_id()
    {
        $this->mockExternalServices();

        $registrationData1 = $this->getValidRegistrationData();
        $registrationData2 = $this->getValidRegistrationData();
        $registrationData2['email'] = 'jane@example.com';

        $this->post('/register', $registrationData1);
        $this->post('/register', $registrationData2);

        $user1 = User::where('email', 'john@example.com')->first();
        $user2 = User::where('email', 'jane@example.com')->first();

        $this->assertNotEquals($user1->xendit_external_id, $user2->xendit_external_id);
        $this->assertStringStartsWith('AMAZING-REG-', $user1->xendit_external_id);
        $this->assertStringStartsWith('AMAZING-REG-', $user2->xendit_external_id);
    }

    /**
     * Create test data
     */
    private function createTestData(): void
    {
        JerseySize::factory()->create(['name' => 'S', 'active' => 1]);
        JerseySize::factory()->create(['name' => 'M', 'active' => 1]);
        JerseySize::factory()->create(['name' => 'L', 'active' => 1]);

        RaceCategory::factory()->create(['name' => '5K', 'active' => 1]);
        RaceCategory::factory()->create(['name' => '10K', 'active' => 1]);

        BloodType::factory()->create(['name' => 'A', 'active' => 1]);
        BloodType::factory()->create(['name' => 'B', 'active' => 1]);

        EventSource::factory()->create(['name' => 'Instagram', 'active' => 1]);
        EventSource::factory()->create(['name' => 'Facebook', 'active' => 1]);

        TicketType::factory()->create([
            'name' => 'Early Bird',
            'price' => 50000,
            'quota' => 100,
            'registered_count' => 0,
            'active' => 1
        ]);
    }

    /**
     * Mock external services
     */
    private function mockExternalServices(): void
    {
        // Mock reCAPTCHA
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9
            ], 200)
        ]);

        // Mock Xendit
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_' . uniqid(),
                'invoice_url' => 'https://checkout.xendit.co/invoice_' . uniqid(),
                'status' => 'PENDING'
            ], 200)
        ]);
    }

    /**
     * Get valid registration data
     */
    private function getValidRegistrationData(): array
    {
        return [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => BloodType::first()->id,
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+6281234567891',
            'address' => '123 Test Street, Test City',
            'race_category_id' => RaceCategory::first()->id,
            'jersey_size_id' => JerseySize::first()->id,
            'ticket_type_id' => TicketType::first()->id,
            'event_source_id' => EventSource::first()->id,
            'terms_accepted' => '1',
            'g-recaptcha-response' => 'valid_recaptcha_response'
        ];
    }
}