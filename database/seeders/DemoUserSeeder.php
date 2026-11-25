<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new RuntimeException(
                'Demo accounts are restricted to local and testing environments.'
            );
        }

        $password = config('edusync.demo_password');

        if (! is_string($password) || strlen($password) < 12 || strlen($password) > 72) {
            throw new RuntimeException(
                'EDUSYNC_DEMO_PASSWORD must contain 12-72 characters.'
            );
        }

        $roles = [
            'admin',
            'teacher',
            'student',
            'parent',
            'school',
        ];

        DB::transaction(function () use ($roles, $password) {
            foreach ($roles as $role) {
                $user = User::firstOrCreate(
                    ['email' => $role . '@edusync.test'],
                    [
                        'name' => 'Demo ' . ucfirst($role),
                        'password' => Hash::make($password),
                    ]
                );

                $user->syncRoles([$role]);
            }
        });

        $this->command?->info(
            'Five EduSync demo accounts initialized.'
        );
    }
}