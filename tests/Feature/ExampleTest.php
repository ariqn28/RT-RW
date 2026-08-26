<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_redirects_home_to_login()
    {
        $response = $this->get('/');

        // Root '/' mengarahkan ke halaman login untuk guest
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
