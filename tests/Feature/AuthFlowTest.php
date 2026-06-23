<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_and_forgot_password_page_can_be_opened(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('login', false);

        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('reset', false);
    }

    public function test_admin_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@example.test',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_forgot_password_sends_reset_status_for_registered_email(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@example.test',
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $this->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => $user->email,
            ])
            ->assertRedirect('/forgot-password')
            ->assertSessionHas('status');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@example.test',
            'role' => User::ROLE_OWNER,
            'is_admin' => true,
        ]);

        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ])->assertRedirect(route('login'));

        $user->refresh();

        $this->assertTrue(Hash::check('passwordBaru123', $user->password));
    }
}
