<?php

namespace App\Http\Controllers;

use App\Models\IdpCompetency;
use App\Models\IdpCompetencyGroup;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class MasterIdpController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_master_idp', only: ['index']),
            new Middleware('can:tambah_master_idp', only: ['storeGroup', 'storeCompetency', 'copyGroup']),
            new Middleware('can:edit_master_idp', only: ['updateGroup', 'updateCompetency']),
            new Middleware('can:hapus_master_idp', only: ['destroyGroup', 'destroyCompetency']),
        ];
    }

    /**
     * Display listing of IDP competency groups and competencies.
     */
    public function index(): View
    {
        $groups = IdpCompetencyGroup::with('competencies')
            ->withCount('competencies')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $positions = Position::orderBy('nama_jabatan')->get();

        return view('master-idp.index', compact('groups', 'positions'));
    }

    /**
     * Store a newly created competency group (chart title).
     */
    public function storeGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_position' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $maxOrder = IdpCompetencyGroup::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        IdpCompetencyGroup::create($validated);

        return redirect()->route('master-idp.index')
            ->with('active_position', $validated['target_position'])
            ->with('success', 'Kelompok Chart IDP berhasil ditambahkan.');
    }

    /**
     * Copy an existing competency group and all its competencies to a target position.
     */
    public function copyGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source_group_id' => 'required|exists:idp_competency_groups,id',
            'target_position' => 'required|string|max:100',
        ]);

        $sourceGroup = IdpCompetencyGroup::with('competencies')->findOrFail($validated['source_group_id']);
        $maxOrder = IdpCompetencyGroup::where('target_position', $validated['target_position'])->max('order') ?? 0;

        $newGroup = IdpCompetencyGroup::create([
            'title' => $sourceGroup->title,
            'target_position' => $validated['target_position'],
            'description' => $sourceGroup->description,
            'order' => $maxOrder + 1,
        ]);

        foreach ($sourceGroup->competencies as $comp) {
            $newGroup->competencies()->create([
                'name' => $comp->name,
                'code' => $comp->code,
                'target_score' => $comp->target_score,
                'description' => $comp->description,
                'order' => $comp->order,
            ]);
        }

        return redirect()->route('master-idp.index')
            ->with('active_position', $validated['target_position'])
            ->with('success', "Chart '{$sourceGroup->title}' beserta seluruh kompetensinya berhasil disalin ke jabatan ".ucwords($validated['target_position']).'.');
    }

    /**
     * Update the specified competency group.
     */
    public function updateGroup(Request $request, int|string $id): RedirectResponse
    {
        $group = IdpCompetencyGroup::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_position' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $group->update($validated);

        return redirect()->route('master-idp.index')
            ->with('active_position', $validated['target_position'])
            ->with('success', 'Kelompok Chart IDP berhasil diperbarui.');
    }

    /**
     * Remove the specified competency group.
     */
    public function destroyGroup(int|string $id): RedirectResponse
    {
        $group = IdpCompetencyGroup::findOrFail($id);
        $targetPos = $group->target_position;
        $group->delete();

        return redirect()->route('master-idp.index')
            ->with('active_position', $targetPos)
            ->with('success', 'Kelompok Chart IDP dan seluruh kompetensi di dalamnya berhasil dihapus.');
    }

    /**
     * Store a newly created competency under a specific group.
     */
    public function storeCompetency(Request $request, int|string $groupId): RedirectResponse
    {
        $group = IdpCompetencyGroup::findOrFail($groupId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'target_score' => 'required|numeric|min:1|max:5',
            'description' => 'nullable|string|max:1000',
        ]);

        $maxOrder = $group->competencies()->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;
        $validated['idp_competency_group_id'] = $group->id;

        IdpCompetency::create($validated);

        return redirect()->route('master-idp.index')
            ->with('active_position', $group->target_position)
            ->with('success', "Kompetensi berhasil ditambahkan ke chart '{$group->title}'.");
    }

    /**
     * Update the specified competency.
     */
    public function updateCompetency(Request $request, int|string $id): RedirectResponse
    {
        $competency = IdpCompetency::with('group')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'target_score' => 'required|numeric|min:1|max:5',
            'description' => 'nullable|string|max:1000',
        ]);

        $competency->update($validated);

        return redirect()->route('master-idp.index')
            ->with('active_position', $competency->group?->target_position)
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    /**
     * Remove the specified competency.
     */
    public function destroyCompetency(int|string $id): RedirectResponse
    {
        $competency = IdpCompetency::with('group')->findOrFail($id);
        $targetPos = $competency->group?->target_position;
        $competency->delete();

        return redirect()->route('master-idp.index')
            ->with('active_position', $targetPos)
            ->with('success', 'Kompetensi berhasil dihapus.');
    }
}
