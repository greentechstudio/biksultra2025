<?php

namespace Tests\Feature\Api;

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

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        Config::set('xendit.secret_key', 'test_secret_key');
        Config::set('xendit.base_url', 'https://api.xendit.co');
        
        // Create necessary test data
        $this->createTestData();
    }

    /** @test */
    public function api_can_register_user_successfully()
    {
        // Mock Xendit response
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_api_123',
                'invoice_url' => 'https://checkout.xendit.co/invoice_api_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        $apiData = $this->getValidApiRegistrationData();

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'registration_number',
                'xendit_invoice_url'
            ]
        ]);

        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Registration successful', $responseData['message']);

        $this->assertDatabaseHas('users', [
            'email' => 'api-user@example.com',
            'name' => 'API Test User',
            'status' => 'registered'
        ]);

        $user = User::where('email', 'api-user@example.com')->first();
        $this->assertNotNull($user->xendit_external_id);
        $this->assertNotNull($user->xendit_invoice_id);
        $this->assertStringStartsWith('AMAZING-REG-', $user->xendit_external_id);
    }

    /** @test */
    public function api_registration_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Required field missing
            'email' => 'invalid-email', // Invalid email format
            'whatsapp_number' => '',
            'date_of_birth' => 'invalid-date',
            'gender' => 'invalid-gender',
        ];

        $response = $this->postJson('/api/register', $invalidData);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);

        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Validation failed', $responseData['message']);
        $this->assertArrayHasKey('errors', $responseData);
    }

    /** @test */
    public function api_registration_fails_when_ticket_quota_full()
    {
        $ticketType = TicketType::first();
        $ticketType->update([
            'quota' => 1,
            'registered_count' => 1
        ]);

        $apiData = $this->getValidApiRegistrationData();
        $apiData['ticket_type_id'] = $ticketType->id;

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ticket quota for this category is full.'
        ]);
    }

    /** @test */
    public function api_registration_fails_with_duplicate_email()
    {
        // Create existing user
        User::factory()->create(['email' => 'api-user@example.com']);

        $apiData = $this->getValidApiRegistrationData();

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);

        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertArrayHasKey('errors', $responseData);
    }

    /** @test */
    public function api_handles_xendit_service_failure()
    {
        // Mock Xendit failure
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'error_code' => 'INVALID_API_KEY',
                'message' => 'API key is invalid'
            ], 401)
        ]);

        $apiData = $this->getValidApiRegistrationData();

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false
        ]);

        // User should be created but invoice creation failed
        $this->assertDatabaseHas('users', [
            'email' => 'api-user@example.com'
        ]);
    }

    /** @test */
    public function api_returns_proper_error_for_server_exception()
    {
        // Force an exception by using invalid ticket_type_id
        $apiData = $this->getValidApiRegistrationData();
        $apiData['ticket_type_id'] = 999999; // Non-existent ID

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Internal server error'
        ]);
    }

    /** @test */
    public function api_creates_user_with_random_password()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_api_123',
                'invoice_url' => 'https://checkout.xendit.co/invoice_api_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        $apiData = $this->getValidApiRegistrationData();

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(201);

        $user = User::where('email', 'api-user@example.com')->first();
        
        // API registrations use random passwords
        $this->assertNotNull($user->password);
        // Password should be hashed
        $this->assertNotEquals('password123', $user->password);
    }

    /** @test */
    public function api_increments_ticket_registered_count()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_api_123',
                'invoice_url' => 'https://checkout.xendit.co/invoice_api_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        $ticketType = TicketType::first();
        $initialCount = $ticketType->registered_count;

        $apiData = $this->getValidApiRegistrationData();
        $apiData['ticket_type_id'] = $ticketType->id;

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(201);

        $ticketType->refresh();
        $this->assertEquals($initialCount + 1, $ticketType->registered_count);
    }

    /** @test */
    public function api_returns_invoice_url_in_response()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_api_123',
                'invoice_url' => 'https://checkout.xendit.co/invoice_api_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        $apiData = $this->getValidApiRegistrationData();

        $response = $this->postJson('/api/register', $apiData);

        $response->assertStatus(201);
        $response->assertJsonPath('user.xendit_invoice_url', 'https://checkout.xendit.co/invoice_api_123');
    }

    /** @test */
    public function api_validates_required_fields()
    {
        $incompleteData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            // Missing required fields
        ];

        $response = $this->postJson('/api/register', $incompleteData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'whatsapp_number',
            'date_of_birth',
            'gender',
            'blood_type_id',
            'emergency_contact_name',
            'emergency_contact_phone',
            'address',
            'race_category_id',
            'jersey_size_id',
            'ticket_type_id'
        ]);
    }

    /**
     * Create test data
     */
    private function createTestData(): void
    {
        JerseySize::factory()->create(['name' => 'S', 'active' => 1]);
        RaceCategory::factory()->create(['name' => '5K', 'active' => 1]);
        BloodType::factory()->create(['name' => 'A', 'active' => 1]);
        EventSource::factory()->create(['name' => 'API', 'active' => 1]);

        TicketType::factory()->create([
            'name' => 'Regular',
            'price' => 75000,
            'quota' => 500,
            'registered_count' => 0,
            'active' => 1
        ]);
    }

    /**
     * Get valid API registration data
     */
    private function getValidApiRegistrationData(): array
    {
        return [
            'name' => 'API Test User',
            'email' => 'api-user@example.com',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => BloodType::first()->id,
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'API Test Address',
            'race_category_id' => RaceCategory::first()->id,
            'jersey_size_id' => JerseySize::first()->id,
            'ticket_type_id' => TicketType::first()->id,
            'event_source_id' => EventSource::first()->id,
            'terms_accepted' => '1'
        ];
    }
}