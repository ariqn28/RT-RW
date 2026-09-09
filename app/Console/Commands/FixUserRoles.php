<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Memperbaiki role user yang kosong atau tidak valid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== FIX USER ROLES ===');
        $this->info('Memeriksa role pengguna...');

        $validRoles = ['admin', 'rw', 'rt', 'warga'];

        $updated = User::where(function ($query) use ($validRoles) {
            $query->whereNotIn('role', $validRoles)
                ->orWhereNull('role');
        })->update([
            'role' => 'warga',
        ]);

        $this->info(
            "Jumlah pengguna yang diperbaiki role-nya: {$updated}"
        );

        $this->line('');
        $this->info('=== REKAP PENGGUNA BERDASARKAN ROLE ===');

        $counts = User::select(
            'role',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('role')
            ->orderBy('role')
            ->pluck('total', 'role');

        foreach ($validRoles as $role) {
            $total = $counts->get($role, 0);

            $this->line(
                "Role [{$role}]: {$total} pengguna"
            );
        }

        $this->line('');
        $this->info('Perbaikan role selesai.');

        return Command::SUCCESS;
    }
}

