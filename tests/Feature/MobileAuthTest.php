<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_user_can_login_via_mobile_api_and_receive_token()
    {
        $user = User::create([
            'name' => 'Warga Mobile',
            'email' => 'warga.mobile@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'warga',
            'nik' => '1111111111111111',
            'alamat' => 'Jl. Mobile No. 1',
        ]);

        $response = $this->postJson('/api/mobile/login', [
            'email' => 'warga.mobile@example.com',
            'password' => '12345678',
            'device_name' => 'iPhone Test',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
                'token_type',
            ])
            ->assertJsonPath('user.email', 'warga.mobile@example.com')
            ->assertJsonPath('user.role', 'warga');

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    public function test_get_request_to_mobile_login_returns_guidance_payload()
    {
        $response = $this->getJson('/api/mobile/login');

        $response->assertStatus(405)
            ->assertJsonPath('message', 'Gunakan method POST untuk login mobile.')
            ->assertJsonPath('hint', 'Kirim request POST ke endpoint ini dari aplikasi mobile.');
    }
}
