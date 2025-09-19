<?php

namespace Tests\Unit\Simple;

use Tests\TestCase;

class NightrunRouteTest extends TestCase
{
    /**
     * Test that nightrun route exists and is accessible
     */
    public function test_nightrun_route_exists()
    {
        // Test GET route
        $routes = collect(\Route::getRoutes())->filter(function($route) {
            return $route->uri() === 'nightrun' && in_array('GET', $route->methods());
        });
        
        $this->assertCount(1, $routes, 'GET /nightrun route should exist');
        
        // Test POST route
        $postRoutes = collect(\Route::getRoutes())->filter(function($route) {
            return $route->uri() === 'nightrun' && in_array('POST', $route->methods());
        });
        
        $this->assertCount(1, $postRoutes, 'POST /nightrun route should exist');
    }

    /**
     * Test that old register route no longer exists
     */
    public function test_old_register_route_removed()
    {
        $routes = collect(\Route::getRoutes())->filter(function($route) {
            return $route->uri() === 'register' && in_array('GET', $route->methods());
        });
        
        $this->assertCount(0, $routes, 'GET /register route should be removed');
    }

    /**
     * Test that route name is still 'register'
     */
    public function test_route_name_unchanged()
    {
        $route = \Route::getRoutes()->getByName('register');
        
        $this->assertNotNull($route, 'Route named "register" should exist');
        $this->assertEquals('nightrun', $route->uri(), 'Route should point to /nightrun');
    }
}