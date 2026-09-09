<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_rt_and_redirects_to_rt_dashboard()
    {
        $response = $this->post('/register', [
            'name' => 'Pak RT Baru',
            'email' => 'rt.baru@example.com',
            'role' => 'rt',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rt.baru@example.com',
            'role' => 'rt',
        ]);

        $response->assertRedirect(route('dashboard.rt'));
    }

    public function test_unauthorized_user_cannot_download_other_users_document()
    {
        Storage::fake('local');

        $owner = User::factory()->create([
            'role' => 'warga',
        ]);

        $otherWarga = User::factory()->create([
            'role' => 'warga',
        ]);

        $file = UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf');
        $path = $file->store('pengajuan_files', 'local');

        $pengajuan = Pengajuan::create([
            'user_id' => $owner->id,
            'jenis_surat' => 'Surat Pengantar',
            'nama' => 'Warga A',
            'nik' => '3273010101000001',
            'alamat' => 'Jl. Merdeka No. 1',
            'alasan' => 'Untuk keperluan pengurusan KTP',
            'status' => 'baru',
            'file_path' => $path,
        ]);

        // Other warga attempting to download should receive 403
        $response = $this->actingAs($otherWarga)->get(route('pengajuan.download', $pengajuan->id));
        $response->assertStatus(403);

        // Owner should be allowed to download
        $ownerResponse = $this->actingAs($owner)->get(route('pengajuan.download', $pengajuan->id));
        $ownerResponse->assertStatus(200);

        // RT should be allowed to download
        $rtUser = User::factory()->create(['role' => 'rt']);
        $rtResponse = $this->actingAs($rtUser)->get(route('pengajuan.download', $pengajuan->id));
        $rtResponse->assertStatus(200);
    }
}

