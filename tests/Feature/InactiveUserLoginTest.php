<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InactiveUserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'npk' => 'TEST999',
            'password' => Hash::make('password123'),
            'status' => 'nonaktif',
        ]);

        $response = $this->post('/login', [
            'npk' => 'TEST999',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('npk');
        $this->assertGuest();
    }

    public function test_active_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'npk' => 'TEST111',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'npk' => 'TEST111',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
