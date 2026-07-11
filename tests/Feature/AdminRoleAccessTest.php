<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_routes(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/users/manage')->assertRedirect('/login');
    }

    public function test_staff_is_blocked_from_admin_routes(): void
    {
        $staff = User::factory()->create([
            'role' => User::ROLE_STAFF,
            'is_admin' => false,
        ]);

        $this->actingAs($staff);

        $this->get('/admin')->assertForbidden();
        $this->get('/admin/users/manage')->assertForbidden();
    }

    public function test_non_owner_cannot_mutate_user_management_routes(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_admin' => true,
        ]);

        $target = User::factory()->create([
            'role' => User::ROLE_STAFF,
            'is_admin' => false,
        ]);

        $this->actingAs($admin);

        $this->post(route('admin.store', ['focus' => 'users']), [
            'name' => 'Forbidden User',
            'email' => 'forbidden@example.test',
            'password' => 'password123',
            'role' => User::ROLE_STAFF,
            'is_admin' => false,
        ])->assertForbidden();

        $this->put(route('admin.update', ['focus' => 'users', 'record' => $target->id]), [
            'name' => 'Updated Forbidden',
            'email' => $target->email,
            'role' => User::ROLE_STAFF,
            'is_admin' => false,
        ])->assertForbidden();

        $this->delete(route('admin.destroy', ['focus' => 'users', 'record' => $target->id]))
            ->assertForbidden();
    }
}
