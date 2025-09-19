<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\XenditService;
use App\Models\User;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class XenditServiceTest extends TestCase
{
    use RefreshDatabase;

    private $xenditService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        Config::set('xendit.secret_key', 'test_secret_key');
        Config::set('xendit.base_url', 'https://api.xendit.co');
        Config::set('xendit.webhook_token', 'test_webhook_token');
        
        $this->xenditService = new XenditService();
    }

    /** @test */
    public function it_can_create_invoice_for_valid_user()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        $user = User::factory()->create(['ticket_type_id' => $ticketType->id]);

        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'invoice_123',
                'invoice_url' => 'https://checkout.xendit.co/invoice_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        // Act
        $result = $this->xenditService->createInvoice($user);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('invoice_123', $result['data']['id']);
        $this->assertEquals('https://checkout.xendit.co/invoice_123', $result['data']['invoice_url']);
        
        Http::assertSent(function ($request) use ($user, $ticketType) {
            $payload = $request->data();
            return $payload['external_id'] === $user->xendit_external_id &&
                   $payload['amount'] == $ticketType->price &&
                   $payload['payer_email'] === $user->email;
        });
    }

    /** @test */
    public function it_validates_price_against_database()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        $user = User::factory()->create(['ticket_type_id' => $ticketType->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Amount mismatch');
        
        // Try to create invoice with wrong amount
        $this->xenditService->createInvoice($user, 30000); // Wrong amount
    }

    /** @test */
    public function it_throws_exception_when_api_key_missing()
    {
        // Arrange
        Config::set('xendit.secret_key', '');

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Xendit API key is not configured');
        
        new XenditService();
    }

    /** @test */
    public function it_can_process_webhook_for_individual_payment()
    {
        // Arrange
        $user = User::factory()->create([
            'xendit_external_id' => 'AMAZING-REG-123-456',
            'payment_status' => 'pending'
        ]);

        $webhookPayload = [
            'id' => 'invoice_123',
            'external_id' => 'AMAZING-REG-123-456',
            'status' => 'PAID',
            'amount' => 50000
        ];

        // Act
        $result = $this->xenditService->processWebhook($webhookPayload);

        // Assert
        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertEquals('paid', $user->payment_status);
        $this->assertNotNull($user->paid_at);
    }

    /** @test */
    public function it_can_process_webhook_for_collective_payment()
    {
        // Arrange
        $externalId = 'AMAZING-COLLECTIVE-123-456';
        $users = User::factory()->count(3)->create([
            'xendit_external_id' => $externalId,
            'payment_status' => 'pending'
        ]);

        $webhookPayload = [
            'id' => 'invoice_123',
            'external_id' => $externalId,
            'status' => 'PAID',
            'amount' => 150000
        ];

        // Act
        $result = $this->xenditService->processWebhook($webhookPayload);

        // Assert
        $this->assertTrue($result);
        
        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals('paid', $user->payment_status);
            $this->assertNotNull($user->paid_at);
        }
    }

    /** @test */
    public function it_can_create_collective_invoice()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        $users = User::factory()->count(3)->create(['ticket_type_id' => $ticketType->id]);
        
        $primaryUser = $users[0];
        $participantDetails = $users->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'amount' => $user->registration_fee
            ];
        })->toArray();

        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'collective_invoice_123',
                'invoice_url' => 'https://checkout.xendit.co/collective_invoice_123',
                'status' => 'PENDING'
            ], 200)
        ]);

        // Act
        $result = $this->xenditService->createCollectiveInvoice(
            $primaryUser,
            $participantDetails,
            150000,
            'Test Collective Registration'
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('collective_invoice_123', $result['invoice_id']);
        $this->assertEquals(150000, $result['amount']);
        
        Http::assertSent(function ($request) {
            $payload = $request->data();
            return $payload['amount'] == 150000 &&
                   str_contains($payload['description'], 'Collective Registration');
        });
    }

    /** @test */
    public function it_handles_xendit_api_failure_gracefully()
    {
        // Arrange
        $ticketType = TicketType::factory()->create(['price' => 50000]);
        $user = User::factory()->create(['ticket_type_id' => $ticketType->id]);

        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'error_code' => 'INVALID_API_KEY',
                'message' => 'API key is invalid'
            ], 401)
        ]);

        // Act
        $result = $this->xenditService->createInvoice($user);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContains('API key is invalid', $result['message']);
    }

    /** @test */
    public function it_validates_webhook_signature()
    {
        // Arrange
        $payload = ['test' => 'data'];
        $invalidSignature = 'invalid_signature';

        // Act
        $result = $this->xenditService->validateWebhookSignature($payload, $invalidSignature);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_returns_false_for_invalid_webhook_payload()
    {
        // Arrange
        $invalidPayload = []; // Missing required fields

        // Act
        $result = $this->xenditService->processWebhook($invalidPayload);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_handles_user_not_found_in_webhook()
    {
        // Arrange
        $webhookPayload = [
            'id' => 'invoice_123',
            'external_id' => 'NON_EXISTENT_ID',
            'status' => 'PAID',
            'amount' => 50000
        ];

        // Act
        $result = $this->xenditService->processWebhook($webhookPayload);

        // Assert
        $this->assertFalse($result);
    }
}