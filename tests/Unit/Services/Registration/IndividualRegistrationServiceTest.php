<?php

namespace Tests\Unit\Services\Registration;

use Tests\TestCase;
use App\Services\Registration\IndividualRegistrationService;
use App\Services\XenditService;
use App\Services\RandomPasswordService;
use App\Services\WhatsAppService;
use App\Models\User;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class IndividualRegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    private $xenditService;
    private $randomPasswordService;
    private $whatsappService;
    private $registrationService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->xenditService = Mockery::mock(XenditService::class);
        $this->randomPasswordService = Mockery::mock(RandomPasswordService::class);
        $this->whatsappService = Mockery::mock(WhatsAppService::class);
        
        $this->registrationService = new IndividualRegistrationService(
            $this->xenditService,
            $this->randomPasswordService,
            $this->whatsappService
        );
    }

    /** @test */
    public function it_can_register_user_with_manual_password()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => 1,
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address',
            'race_category_id' => 1,
            'jersey_size_id' => 1,
            'ticket_type_id' => $ticketType->id,
            'event_source_id' => 1
        ];

        $this->xenditService->shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'invoice_123',
                    'invoice_url' => 'https://checkout.xendit.co/invoice_123'
                ]
            ]);

        $this->whatsappService->shouldReceive('sendMessage')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->registrationService->registerWithManualPassword($userData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertEquals('john@example.com', $result['user']->email);
        $this->assertNotNull($result['user']->xendit_external_id);
        $this->assertEquals('https://checkout.xendit.co/invoice_123', $result['invoice_url']);
        
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'status' => 'registered'
        ]);
    }

    /** @test */
    public function it_can_register_user_with_random_password()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        
        $userData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1992-05-15',
            'gender' => 'female',
            'blood_type_id' => 2,
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address 2',
            'race_category_id' => 2,
            'jersey_size_id' => 2,
            'ticket_type_id' => $ticketType->id,
            'event_source_id' => 1
        ];

        $this->randomPasswordService->shouldReceive('generateAndSendPassword')
            ->once()
            ->andReturn([
                'success' => true,
                'password_sent' => true
            ]);

        $this->xenditService->shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'invoice_456',
                    'invoice_url' => 'https://checkout.xendit.co/invoice_456'
                ]
            ]);

        $this->whatsappService->shouldReceive('sendMessage')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->registrationService->registerWithRandomPassword($userData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertEquals('jane@example.com', $result['user']->email);
        $this->assertTrue($result['password_sent']);
        
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'status' => 'registered'
        ]);
    }

    /** @test */
    public function it_handles_xendit_invoice_creation_failure()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => 1,
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address',
            'race_category_id' => 1,
            'jersey_size_id' => 1,
            'ticket_type_id' => $ticketType->id
        ];

        $this->xenditService->shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'success' => false,
                'message' => 'Xendit API error'
            ]);

        // Act
        $result = $this->registrationService->registerWithManualPassword($userData);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContains('Failed to create payment invoice', $result['message']);
    }

    /** @test */
    public function it_handles_random_password_generation_failure()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => 1,
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address',
            'race_category_id' => 1,
            'jersey_size_id' => 1,
            'ticket_type_id' => $ticketType->id
        ];

        $this->randomPasswordService->shouldReceive('generateAndSendPassword')
            ->once()
            ->andReturn([
                'success' => false,
                'message' => 'WhatsApp service unavailable'
            ]);

        // Act
        $result = $this->registrationService->registerWithRandomPassword($userData);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContains('Failed to generate and send password', $result['message']);
    }

    /** @test */
    public function it_increments_ticket_type_registered_count()
    {
        // Arrange
        $ticketType = TicketType::factory()->create([
            'price' => 50000,
            'registered_count' => 5
        ]);
        
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => 1,
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address',
            'race_category_id' => 1,
            'jersey_size_id' => 1,
            'ticket_type_id' => $ticketType->id
        ];

        $this->xenditService->shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'invoice_123',
                    'invoice_url' => 'https://checkout.xendit.co/invoice_123'
                ]
            ]);

        $this->whatsappService->shouldReceive('sendMessage')
            ->once()
            ->andReturn(true);

        // Act
        $this->registrationService->registerWithManualPassword($userData);

        // Assert
        $ticketType->refresh();
        $this->assertEquals(6, $ticketType->registered_count);
    }

    /** @test */
    public function it_generates_unique_registration_number()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        
        $userData1 = [
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => 'password123',
            'whatsapp_number' => '+6281234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type_id' => 1,
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+6281234567891',
            'address' => 'Test Address',
            'race_category_id' => 1,
            'jersey_size_id' => 1,
            'ticket_type_id' => $ticketType->id
        ];

        $userData2 = [
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => 'password123',
            'whatsapp_number' => '+6281234567892',
            'date_of_birth' => '1991-02-02',
            'gender' => 'female',
            'blood_type_id' => 2,
            'emergency_contact_name' => 'Emergency Contact 2',
            'emergency_contact_phone' => '+6281234567893',
            'address' => 'Test Address 2',
            'race_category_id' => 2,
            'jersey_size_id' => 2,
            'ticket_type_id' => $ticketType->id
        ];

        $this->xenditService->shouldReceive('createInvoice')
            ->twice()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'invoice_123',
                    'invoice_url' => 'https://checkout.xendit.co/invoice_123'
                ]
            ]);

        $this->whatsappService->shouldReceive('sendMessage')
            ->twice()
            ->andReturn(true);

        // Act
        $result1 = $this->registrationService->registerWithManualPassword($userData1);
        $result2 = $this->registrationService->registerWithManualPassword($userData2);

        // Assert
        $this->assertNotEquals(
            $result1['user']->registration_number,
            $result2['user']->registration_number
        );
        
        $this->assertStringStartsWith('ASR' . date('Y'), $result1['user']->registration_number);
        $this->assertStringStartsWith('ASR' . date('Y'), $result2['user']->registration_number);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}