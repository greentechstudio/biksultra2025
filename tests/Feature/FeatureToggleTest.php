<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureToggleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that wakaf registration is disabled by default
     */
    public function test_wakaf_registration_is_disabled_by_default()
    {
        $response = $this->getJson('/api/features/wakaf_registration');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'feature' => 'wakaf_registration',
                         'enabled' => false,
                         'status' => 'disabled'
                     ]
                 ]);
    }

    /**
     * Test that collective registration is disabled by default
     */
    public function test_collective_registration_is_disabled_by_default()
    {
        $response = $this->getJson('/api/features/collective_registration');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'feature' => 'collective_registration',
                         'enabled' => false,
                         'status' => 'disabled'
                     ]
                 ]);
    }

    /**
     * Test that individual registration is enabled by default
     */
    public function test_individual_registration_is_enabled_by_default()
    {
        $response = $this->getJson('/api/features/individual_registration');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'feature' => 'individual_registration',
                         'enabled' => true,
                         'status' => 'active'
                     ]
                 ]);
    }

    /**
     * Test enabled registration types endpoint
     */
    public function test_enabled_registration_types_returns_only_individual()
    {
        $response = $this->getJson('/api/registration-types');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'total_enabled' => 1
                     ]
                 ]);
        
        $enabledTypes = $response->json('data.enabled_types');
        $this->assertCount(1, $enabledTypes);
        $this->assertEquals('individual', $enabledTypes[0]['type']);
    }

    /**
     * Test that wakaf controller returns 403 when feature is disabled
     */
    public function test_wakaf_controller_returns_403_when_disabled()
    {
        $response = $this->postJson('/api/wakaf/register', [
            'nama_lengkap' => 'Test User',
            'email' => 'test@example.com',
            'no_wa' => '08123456789',
            'jumlah_wakaf' => 50000,
            'g-recaptcha-response' => 'test-token'
        ]);
        
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'feature_disabled' => true
                 ]);
    }

    /**
     * Test that collective controller returns 403 when feature is disabled
     */
    public function test_collective_controller_returns_403_when_disabled()
    {
        $response = $this->postJson('/api/collective/register', [
            'nama_lengkap' => 'Test User',
            'email' => 'test@example.com',
            'no_wa' => '08123456789',
            'ticket_type_id' => 1,
            'participants' => [],
            'g-recaptcha-response' => 'test-token'
        ]);
        
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'feature_disabled' => true
                 ]);
    }
}