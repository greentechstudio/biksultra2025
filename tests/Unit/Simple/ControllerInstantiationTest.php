<?php

namespace Tests\Unit\Simple;

use Tests\TestCase;
use App\Http\Controllers\IndividualRegistrationController;
use App\Http\Controllers\CollectiveRegistrationController;
use App\Http\Controllers\WakafRegistrationController;

class ControllerInstantiationTest extends TestCase
{
    /**
     * Test that our refactored controllers can be instantiated correctly.
     *
     * @return void
     */
    public function test_individual_registration_controller_can_be_instantiated()
    {
        $controller = app(IndividualRegistrationController::class);
        $this->assertInstanceOf(IndividualRegistrationController::class, $controller);
    }

    /**
     * Test that our refactored collective registration controller can be instantiated.
     *
     * @return void
     */
    public function test_collective_registration_controller_can_be_instantiated()
    {
        $controller = app(CollectiveRegistrationController::class);
        $this->assertInstanceOf(CollectiveRegistrationController::class, $controller);
    }

    /**
     * Test that our refactored wakaf registration controller can be instantiated.
     *
     * @return void
     */
    public function test_wakaf_registration_controller_can_be_instantiated()
    {
        $controller = app(WakafRegistrationController::class);
        $this->assertInstanceOf(WakafRegistrationController::class, $controller);
    }

    /**
     * Test that controllers have the expected methods.
     *
     * @return void
     */
    public function test_controllers_have_expected_methods()
    {
        $individualController = app(IndividualRegistrationController::class);
        $this->assertTrue(method_exists($individualController, 'register'));
        $this->assertTrue(method_exists($individualController, 'getFormData'));

        $collectiveController = app(CollectiveRegistrationController::class);
        $this->assertTrue(method_exists($collectiveController, 'register'));
        $this->assertTrue(method_exists($collectiveController, 'getFormData'));
        $this->assertTrue(method_exists($collectiveController, 'getLimits'));

        $wakafController = app(WakafRegistrationController::class);
        $this->assertTrue(method_exists($wakafController, 'register'));
        $this->assertTrue(method_exists($wakafController, 'webhook'));
        $this->assertTrue(method_exists($wakafController, 'getInfo'));
        $this->assertTrue(method_exists($wakafController, 'getHistory'));
    }
}