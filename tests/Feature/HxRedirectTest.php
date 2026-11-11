<?php

namespace Tests\Feature;

use App\Models\AssessmentAspect;
use App\Models\AssessmentCategory;
use App\Models\Department;
use App\Models\IdpCompetency;
use App\Models\IdpCompetencyGroup;
use App\Models\Karyawan;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class HxRedirectTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        foreach ([
            'lihat_role', 'tambah_role', 'edit_role', 'hapus_role',
            'lihat_user', 'tambah_user', 'edit_user', 'hapus_user',
            'lihat_departemen', 'tambah_departemen', 'edit_departemen', 'hapus_departemen',
            'lihat_jabatan', 'tambah_jabatan', 'edit_jabatan', 'hapus_jabatan',
            'lihat_master_idp', 'tambah_master_idp', 'edit_master_idp', 'hapus_master_idp',
            'lihat_master_assessment', 'tambah_master_assessment', 'edit_master_assessment', 'hapus_master_assessment',
            'lihat_karyawan', 'tambah_karyawan', 'edit_karyawan', 'hapus_karyawan',
        ] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);
        $this->admin->assignRole($roleAdmin);
    }

    public function test_role_store_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('role.store'), [
                'name' => 'Manager',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('role.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Manager']);
    }

    public function test_role_update_with_hx_request_returns_hx_redirect(): void
    {
        $role = Role::create(['name' => 'Supervisor', 'guard_name' => 'web']);

        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->put(route('role.update', $role->id), [
                'name' => 'Senior Supervisor',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('role.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Senior Supervisor']);
    }

    public function test_user_management_store_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('user-management.store'), [
                'name' => 'Budi Santoso',
                'npk' => 'BUDI123',
                'email' => 'budi@example.com',
                'password' => 'secret12345',
                'role' => 'admin',
                'status' => 'aktif',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('user-management.index'));
        $this->assertDatabaseHas('users', ['npk' => 'BUDI123']);
    }

    public function test_department_store_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('department.store'), [
                'nama_departemen' => 'Information Technology',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('department.index'));
        $this->assertDatabaseHas('departments', ['nama_departemen' => 'Information Technology']);
    }

    public function test_position_store_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('position.store'), [
                'nama_jabatan' => 'DevOps Engineer',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('position.index'));
        $this->assertDatabaseHas('positions', ['nama_jabatan' => 'DevOps Engineer']);
    }

    public function test_master_idp_store_group_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('master-idp.group.store'), [
                'title' => 'Technical Competencies',
                'target_position' => 'staff',
                'description' => 'Technical evaluation',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-idp.index'));
        $this->assertDatabaseHas('idp_competency_groups', ['title' => 'Technical Competencies']);
    }

    public function test_master_assessment_store_category_with_hx_request_returns_hx_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('master-assessment.category.store'), [
                'name' => 'Leadership Competency',
                'description' => 'Leadership evaluation',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-assessment.index'));
        $this->assertDatabaseHas('assessment_categories', ['name' => 'Leadership Competency']);
    }

    public function test_master_idp_competency_crud_with_hx_request_returns_hx_redirect(): void
    {
        $group = IdpCompetencyGroup::create([
            'title' => 'Technical Competencies',
            'target_position' => 'staff',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('master-idp.competency.store', $group->id), [
                'name' => 'Problem Solving',
                'target_score' => 4.5,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-idp.index'));
        $this->assertDatabaseHas('idp_competencies', ['name' => 'Problem Solving']);

        $competency = IdpCompetency::query()->where('name', 'Problem Solving')->firstOrFail();
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->put(route('master-idp.competency.update', $competency->id), [
                'name' => 'Advanced Problem Solving',
                'target_score' => 5,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-idp.index'));
        $this->assertDatabaseHas('idp_competencies', ['name' => 'Advanced Problem Solving']);
    }

    public function test_master_assessment_aspect_crud_with_hx_request_returns_hx_redirect(): void
    {
        $category = AssessmentCategory::create([
            'name' => 'Leadership Competency',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('master-assessment.aspect.store', $category->id), [
                'name' => 'Communication',
                'target_score' => 4.5,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-assessment.index'));
        $this->assertDatabaseHas('assessment_aspects', ['name' => 'Communication']);

        $aspect = AssessmentAspect::query()->where('name', 'Communication')->firstOrFail();
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->put(route('master-assessment.aspect.update', $aspect->id), [
                'name' => 'Clear Communication',
                'target_score' => 5,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Redirect', route('master-assessment.index'));
        $this->assertDatabaseHas('assessment_aspects', ['name' => 'Clear Communication']);
    }

    public function test_role_destroy_with_hx_request_returns_hx_redirect(): void
    {
        $role = Role::create(['name' => 'TemporaryRole', 'guard_name' => 'web']);

        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->delete(route('role.destroy', $role->id));

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('role.index'));
        $this->assertDatabaseMissing('roles', ['name' => 'TemporaryRole']);
    }

    public function test_employee_crud_with_hx_request_returns_hx_redirect(): void
    {
        $dept = Department::create(['nama_departemen' => 'Engineering']);
        $pos = Position::create(['nama_jabatan' => 'Software Engineer']);

        // Store
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->post(route('employee.store'), [
                'npk' => 'EMP001',
                'nama' => 'John Doe',
                'department_id' => $dept->id,
                'position_id' => $pos->id,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('employee.index'));
        $this->assertDatabaseHas('karyawan', ['npk' => 'EMP001']);

        $emp = Karyawan::where('npk', 'EMP001')->first();

        // Update
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->put(route('employee.update', $emp->id), [
                'npk' => 'EMP001',
                'nama' => 'John Doe Updated',
                'department_id' => $dept->id,
                'position_id' => $pos->id,
            ]);

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('employee.index'));
        $this->assertDatabaseHas('karyawan', ['nama' => 'John Doe Updated']);

        // Destroy
        $response = $this->actingAs($this->admin)
            ->withHeader('HX-Request', 'true')
            ->delete(route('employee.destroy', $emp->id));

        $response->assertStatus(200);
        $response->assertHeader('HX-Location', route('employee.index'));
        $this->assertDatabaseMissing('karyawan', ['id' => $emp->id]);
    }
}
