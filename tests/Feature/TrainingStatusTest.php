<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\RiwayatTraining;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TrainingStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_edit_training_can_update_status_and_upload(): void
    {
        Storage::fake('hrg');

        $dept = Department::create(['nama_departemen' => 'IT']);
        $employee = Karyawan::create([
            'npk' => '12346',
            'nama' => 'Siti Aminah',
            'department_id' => $dept->id,
            'readiness' => 'R1 ready',
            'hav' => 'star',
        ]);

        $perm = Permission::create(['name' => 'edit_training', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'npk' => '12346',
            'role' => 'Head',
            'status' => 'aktif',
        ]);
        $user->givePermissionTo($perm);

        $training = Training::create([
            'id_karyawan' => $employee->id,
            'target_mandatory' => 5,
            'target_skill' => 5,
            'target_managerial' => 5,
        ]);

        $riwayat = RiwayatTraining::create([
            'id_karyawan' => $employee->id,
            'id_training' => $training->id,
            'nama_training' => 'PHP Advanced',
            'kategori' => 'mandatory',
            'tanggal_training' => now()->toDateString(),
        ]);

        // 1. Update status
        $statusResponse = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->patchJson(route('training.update-status', $riwayat->id), [
                'eva1_status' => 'completed',
            ]);

        $statusResponse->assertOk();
        $this->assertEquals('completed', $riwayat->fresh()->eva1_status);

        // 2. Upload EVA
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
        $uploadResponse = $this->actingAs($user)
            ->post(route('training.upload-eva', $riwayat->id), [
                'eva' => 'eva1',
                'file' => $file,
            ]);

        $uploadResponse->assertSessionHas('success');
        $this->assertNotNull($riwayat->fresh()->eva1_lampiran);
    }

    public function test_user_without_edit_training_cannot_update_status_or_upload(): void
    {
        Storage::fake('hrg');

        $dept = Department::create(['nama_departemen' => 'Finance']);
        $employee = Karyawan::create([
            'npk' => '55555',
            'nama' => 'Staff User',
            'department_id' => $dept->id,
            'readiness' => 'R1 ready',
            'hav' => 'star',
        ]);

        $user = User::factory()->create([
            'npk' => '55555',
            'role' => 'user',
            'status' => 'aktif',
        ]);

        $training = Training::create([
            'id_karyawan' => $employee->id,
            'target_mandatory' => 5,
            'target_skill' => 5,
            'target_managerial' => 5,
        ]);

        $riwayat = RiwayatTraining::create([
            'id_karyawan' => $employee->id,
            'id_training' => $training->id,
            'nama_training' => 'Excel Basic',
            'kategori' => 'mandatory',
            'tanggal_training' => now()->toDateString(),
        ]);

        // Status update should be forbidden (403)
        $statusResponse = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->patchJson(route('training.update-status', $riwayat->id), [
                'eva1_status' => 'completed',
            ]);

        $statusResponse->assertForbidden();

        // Upload should be forbidden (403)
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $uploadResponse = $this->actingAs($user)
            ->post(route('training.upload-eva', $riwayat->id), [
                'eva' => 'eva1',
                'file' => $file,
            ]);

        $uploadResponse->assertForbidden();
    }
}
