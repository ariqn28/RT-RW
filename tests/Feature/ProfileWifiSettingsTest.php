<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileWifiSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_info()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'nik' => '3273010101000099',
            'alamat' => 'Jl Baru No. 1',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    public function test_user_must_provide_current_password_to_change_password()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }
}
