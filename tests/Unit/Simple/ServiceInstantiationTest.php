<?php

namespace Tests\Unit\Simple;

use Tests\TestCase;
use App\Services\XenditService;
use App\Services\IndividualRegistrationService;
use App\Services\CollectiveRegistrationService;
use App\Services\WakafRegistrationService;

class ServiceInstantiationTest extends TestCase
{
    /**
     * Test that our refactored services can be instantiated correctly.
     *
     * @return void
     */
    public function test_xendit_service_can_be_instantiated()
    {
        $service = app(XenditService::class);
        $this->assertInstanceOf(XenditService::class, $service);
    }

    /**
     * Test that our refactored individual registration service can be instantiated.
     *
     * @return void
     */
    public function test_individual_registration_service_can_be_instantiated()
    {
        $service = app(IndividualRegistrationService::class);
        $this->assertInstanceOf(IndividualRegistrationService::class, $service);
    }

    /**
     * Test that our refactored collective registration service can be instantiated.
     *
     * @return void
     */
    public function test_collective_registration_service_can_be_instantiated()
    {
        $service = app(CollectiveRegistrationService::class);
        $this->assertInstanceOf(CollectiveRegistrationService::class, $service);
    }

    /**
     * Test that our refactored wakaf registration service can be instantiated.
     *
     * @return void
     */
    public function test_wakaf_registration_service_can_be_instantiated()
    {
        $service = app(WakafRegistrationService::class);
        $this->assertInstanceOf(WakafRegistrationService::class, $service);
    }

    /**
     * Test that services have the expected methods.
     *
     * @return void
     */
    public function test_services_have_expected_methods()
    {
        $xenditService = app(XenditService::class);
        $this->assertTrue(method_exists($xenditService, 'createInvoice'));
        $this->assertTrue(method_exists($xenditService, 'processWebhook'));

        $individualService = app(IndividualRegistrationService::class);
        $this->assertTrue(method_exists($individualService, 'processRegistration'));

        $collectiveService = app(CollectiveRegistrationService::class);
        $this->assertTrue(method_exists($collectiveService, 'processRegistration'));

        $wakafService = app(WakafRegistrationService::class);
        $this->assertTrue(method_exists($wakafService, 'processRegistration'));
    }
}