<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix user roles yang tidak konsisten
        $users_data = [
            [
                'email' => 'ariqn@gmail.com',
                'role' => 'warga',
            ],
            [
                'email' => 'ariqns@gmail.com',
                'role' => 'rt',
            ],
            [
                'email' => 'ariqns280702@gmail.com',
                'role' => 'rw',
            ],
        ];

        foreach ($users_data as $data) {
            User::where('email', $data['email'])->update(['role' => $data['role']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
