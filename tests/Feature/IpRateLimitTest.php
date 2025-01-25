<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IpRateLimitTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ip_rate_limit()
    {
        for ($i = 1; $i <= 10; $i++) {
            $response = $this->postJson('/api/login');
           
          
        }
            $this->postJson('/api/login')->assertStatus(429);
    }
}
