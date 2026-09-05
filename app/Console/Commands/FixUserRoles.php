<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user roles yang tidak konsisten';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== FIX USER ROLES ===');

        // Data user yang benar
        $users_to_fix = [
            'ariqn@gmail.com' => 'warga',
            'ariqns@gmail.com' => 'rt',
            'ariqns280702@gmail.com' => 'rw',
        ];

        foreach ($users_to_fix as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $old_role = $user->role;
                $user->update(['role' => $role]);
                $this->info("✅ {$email}: {$old_role} → {$role}");
            } else {
                $this->warn("⚠️  {$email}: User tidak ditemukan");
            }
        }

        $this->line('');
        $this->info('=== USERS AFTER FIX ===');
        $all = User::select('id', 'email', 'name', 'role')->get();
        $all->each(function ($u) {
            $this->line("#{$u->id} | {$u->email} | {$u->name} | [{$u->role}]");
        });

        $this->line('');
        $this->info('✅ Fix selesai! Silakan login dengan email + password + role yang benar.');
    }
}
