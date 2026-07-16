<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_update_and_delete_another_user(): void
    {
        $owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->actingAs($owner);

        $this->post(route('admin.store', ['focus' => 'users']), [
            'name' => 'QA Staff',
            'email' => 'qa.staff@example.test',
            'password' => 'password123',
            'role' => User::ROLE_STAFF,
            'is_admin' => false,
            'is_active' => true,
        ])->assertRedirect(route('admin.index', ['focus' => 'users', 'mode' => 'manage']));

        $created = User::query()->where('email', 'qa.staff@example.test')->firstOrFail();

        $this->assertSame(User::ROLE_STAFF, $created->role);
        $this->assertFalse($created->is_admin);
        $this->assertTrue($created->is_active);
        $this->assertTrue(Hash::check('password123', $created->password));

        $this->put(route('admin.update', ['focus' => 'users', 'record' => $created->id]), [
            'name' => 'QA Admin',
            'email' => 'qa.staff@example.test',
            'password' => 'password456',
            'role' => User::ROLE_ADMIN,
            'is_admin' => true,
            'is_active' => false,
        ])->assertRedirect(route('admin.index', ['focus' => 'users', 'mode' => 'manage']));

        $created->refresh();

        $this->assertSame('QA Admin', $created->name);
        $this->assertSame(User::ROLE_ADMIN, $created->role);
        $this->assertTrue($created->is_admin);
        $this->assertFalse($created->is_active);
        $this->assertTrue(Hash::check('password456', $created->password));

        $this->delete(route('admin.destroy', ['focus' => 'users', 'record' => $created->id]))
            ->assertRedirect(route('admin.index', ['focus' => 'users', 'mode' => 'manage']));

        $this->assertDatabaseMissing('users', [
            'id' => $created->id,
        ]);
    }

    public function test_owner_cannot_delete_self(): void
    {
        $owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->actingAs($owner);

        $this->delete(route('admin.destroy', ['focus' => 'users', 'record' => $owner->id]))
            ->assertForbidden();
    }
}
