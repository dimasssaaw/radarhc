<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Welcome back');
    }

    public function test_head_can_login_to_the_dashboard_for_the_selected_department(): void
    {
        $departmentId = DB::table('departemen1')->insertGetId([
            'nama_departemen' => 'Human Resources',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $head = User::factory()->create([
            'role' => 'head',
            'id_departemen' => $departmentId,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $head->email,
            'password' => 'password',
            'id_departemen' => $departmentId,
        ]);

        $response->assertRedirect(route('head.dashboard'));
        $this->assertAuthenticatedAs($head);
    }

    public function test_non_head_cannot_login(): void
    {
        $departmentId = DB::table('departemen1')->insertGetId([
            'nama_departemen' => 'Human Resources',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::factory()->create([
            'role' => 'staff',
            'id_departemen' => $departmentId,
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
            'id_departemen' => $departmentId,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_non_head_cannot_open_the_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('head.dashboard'));

        $response->assertForbidden();
    }
}
