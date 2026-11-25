<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RolePermissionSeeder::class);
    }

    private function account(string $role = 'student'): User
    {
        return User::factory()->create()->assignRole($role);
    }

    public function test_guests_cannot_view_or_update_profile(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
        $this->patch(route('profile.update'))->assertRedirect(route('login'));
        $this->put(route('profile.password'))->assertRedirect(route('login'));
    }

    public function test_all_five_authorized_roles_can_open_the_same_profile(): void
    {
        foreach (['admin', 'teacher', 'student', 'parent', 'school'] as $role) {
            $user = $this->account($role);
            $this->flushSession();
            $this->actingAs($user)->get(route('profile'))->assertOk()->assertSee($user->email)->assertViewHas('portalRole', $role);
        }
    }

    public function test_user_without_portal_permission_is_denied(): void
    {
        $user = $this->account();
        $user->roles()->first()->revokePermissionTo('access.student');
        $this->actingAs($user)->get(route('profile'))->assertForbidden();
        $this->patch(route('profile.update'))->assertForbidden();
        $this->put(route('profile.password'))->assertForbidden();
    }

    public function test_user_can_update_only_own_name_and_email(): void
    {
        $user = $this->account();
        $other = $this->account('teacher');
        $oldOtherEmail = $other->email;
        $this->actingAs($user)->from(route('profile'))->patch(route('profile.update'), [
            'name' => 'Nama Baru', 'email' => 'updated@example.test', 'current_password' => 'password',
            'user_id' => $other->id, 'role' => 'admin', 'email_verified_at' => now()->toDateTimeString(),
        ])->assertRedirect(route('profile'))->assertSessionHas('profile_status');
        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('updated@example.test', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertSame($oldOtherEmail, $other->fresh()->email);
    }

    public function test_duplicate_email_and_wrong_password_are_rejected(): void
    {
        $user = $this->account();
        $other = $this->account();
        $oldEmail = $user->email;
        $this->actingAs($user)->from(route('profile'))->patch(route('profile.update'), [
            'name' => 'Nama Baru', 'email' => $other->email, 'current_password' => 'wrong',
        ])->assertSessionHasErrors(['email', 'current_password'], null, 'profile');
        $this->assertSame($oldEmail, $user->fresh()->email);
        $this->assertNull(session()->getOldInput('current_password'));
    }

    public function test_password_change_requires_current_password_and_confirmation(): void
    {
        $user = $this->account();
        $this->actingAs($user)->from(route('profile'))->put(route('profile.password'), [
            'current_password' => 'wrong', 'password' => 'NewSecret12345', 'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['current_password', 'password'], null, 'security');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertNull(session()->getOldInput('password'));
    }

    public function test_valid_password_change_is_hashed_and_current_session_remains_usable(): void
    {
        $user = $this->account();
        $this->actingAs($user)->from(route('profile'))->put(route('profile.password'), [
            'current_password' => 'password', 'password' => 'NewSecret12345', 'password_confirmation' => 'NewSecret12345',
        ])->assertRedirect(route('profile'))->assertSessionHas('security_status');
        $this->assertTrue(Hash::check('NewSecret12345', $user->fresh()->password));
        $this->assertFalse(Hash::check('password', $user->fresh()->password));
        $this->get(route('profile'))->assertOk();
    }
}
