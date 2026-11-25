<?php

namespace App\Support;

use App\Models\User;

final class PortalAccess
{
    /** @return array{portalRole: string, portalLabel: string} */
    public static function resolve(User $user): array
    {
        // Same priority and role + permission checks as the existing dashboard.
        foreach ([
            'admin' => 'Admin',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            'parent' => 'Orang Tua',
            'school' => 'Pihak Sekolah',
        ] as $role => $label) {
            if ($user->hasRole($role) && $user->can('access.' . $role)) {
                return ['portalRole' => $role, 'portalLabel' => $label];
            }
        }

        abort(403, 'Anda tidak memiliki akses ke portal.');
    }
}
