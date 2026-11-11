<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_redirected_to_user_dashboard(): void
    {
        $dept = Department::create(['nama_departemen' => 'IT']);
        $karyawan = Karyawan::create([
            'npk' => '12346',
            'nama' => 'Siti Aminah',
            'department_id' => $dept->id,
            'readiness' => 'R1 ready',
            'hav' => 'star',
        ]);

        $user = User::factory()->create([
            'npk' => '12346',
            'role' => 'Head',
            'status' => 'aktif',
        ]);

        // Standard GET to /dashboard redirects to /user
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertRedirect(route('user.dashboard'));

        // HTMX request to /dashboard returns HX-Redirect
        $hxResponse = $this->actingAs($user)
            ->withHeader('HX-Request', 'true')
            ->get(route('dashboard'));
        $hxResponse->assertHeader('HX-Redirect', route('user.dashboard'));

        // Direct GET to /user loads user dashboard with 200 OK
        $userDashResponse = $this->actingAs($user)->get(route('user.dashboard'));
        $userDashResponse->assertOk();
        $userDashResponse->assertSee('Welcome back');
    }
}
