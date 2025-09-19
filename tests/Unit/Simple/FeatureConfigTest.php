<?php

namespace Tests\Unit\Simple;

use Tests\TestCase;

class FeatureConfigTest extends TestCase
{
    /**
     * Test that feature config loads correctly
     */
    public function test_feature_config_loads_correctly()
    {
        // Test wakaf registration is disabled by default
        $this->assertFalse(config('features.wakaf_registration.enabled'));
        $this->assertEquals('Wakaf Registration', config('features.wakaf_registration.name'));
        
        // Test collective registration is disabled by default
        $this->assertFalse(config('features.collective_registration.enabled'));
        $this->assertEquals('Collective Registration', config('features.collective_registration.name'));
        
        // Test individual registration is enabled by default
        $this->assertTrue(config('features.individual_registration.enabled'));
        $this->assertEquals('Individual Registration', config('features.individual_registration.name'));
    }

    /**
     * Test that disabled messages are configured
     */
    public function test_disabled_messages_are_configured()
    {
        $this->assertNotEmpty(config('features.disabled_messages.wakaf_registration'));
        $this->assertNotEmpty(config('features.disabled_messages.collective_registration'));
        $this->assertNotEmpty(config('features.disabled_messages.default'));
    }

    /**
     * Test feature controller instantiation
     */
    public function test_feature_controller_can_be_instantiated()
    {
        $controller = app(\App\Http\Controllers\FeatureController::class);
        $this->assertInstanceOf(\App\Http\Controllers\FeatureController::class, $controller);
    }
}