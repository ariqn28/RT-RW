<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileWifiSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_wifi_credentials_from_profile_settings()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $envPath = base_path('.env');
        $originalEnv = file_exists($envPath) ? file_get_contents($envPath) : '';

        try {
            $this->withoutMiddleware([VerifyCsrfToken::class]);

            $response = $this->actingAs($user)->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'nik' => '',
                'alamat' => '',
                'wifi_ssid' => 'RT_RW_WIFI',
                'wifi_password' => 'SecurePass123',
            ]);

            $response->assertRedirect(route('profile.edit'));

            $updatedEnv = file_get_contents($envPath);
            $this->assertStringContainsString('WIFI_SSID=RT_RW_WIFI', $updatedEnv);
            $this->assertStringContainsString('WIFI_PASSWORD=SecurePass123', $updatedEnv);
        } finally {
            file_put_contents($envPath, $originalEnv);
        }
    }
}
