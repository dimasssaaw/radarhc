<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAspect;
use App\Models\AssessmentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class MasterAssessmentController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_master_assessment', only: ['index']),
            new Middleware('can:tambah_master_assessment', only: ['storeCategory', 'storeAspect']),
            new Middleware('can:edit_master_assessment', only: ['updateCategory', 'updateAspect']),
            new Middleware('can:hapus_master_assessment', only: ['destroyCategory', 'destroyAspect']),
        ];
    }

    private function hxOrRedirect(Request $request, string $msg): Response|RedirectResponse
    {
        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)
                ->header('HX-Redirect', route('master-assessment.index'));
        }

        return redirect()->route('master-assessment.index')->with('success', $msg);
    }

    /**
     * Display a listing of assessment categories and aspects.
     */
    public function index(): View
    {
        $categories = AssessmentCategory::with('aspects')
            ->withCount('aspects')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('master-assessment.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxOrder = AssessmentCategory::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        AssessmentCategory::create($validated);

        return $this->hxOrRedirect($request, 'Kategori Penilaian Assessment berhasil ditambahkan.');
    }

    /**
     * Update the specified category.
     */
    public function updateCategory(Request $request, int|string $id): Response|RedirectResponse
    {
        $category = AssessmentCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return $this->hxOrRedirect($request, 'Kategori Penilaian Assessment berhasil diperbarui.');
    }

    /**
     * Remove the specified category.
     */
    public function destroyCategory(Request $request, int|string $id): Response|RedirectResponse
    {
        $category = AssessmentCategory::findOrFail($id);
        $category->delete();

        return $this->hxOrRedirect($request, 'Kategori dan seluruh aspek penilaian di dalamnya berhasil dihapus.');
    }

    /**
     * Store a newly created aspect under a category.
     */
    public function storeAspect(Request $request, int|string $categoryId): Response|RedirectResponse
    {
        $category = AssessmentCategory::findOrFail($categoryId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_score' => 'required|numeric|min:1|max:5',
        ]);

        $maxOrder = $category->aspects()->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;
        $validated['category_id'] = $category->id;

        AssessmentAspect::create($validated);

        return $this->hxOrRedirect($request, "Aspek Penilaian berhasil ditambahkan ke kategori '{$category->name}'.");
    }

    /**
     * Update the specified aspect.
     */
    public function updateAspect(Request $request, int|string $id): Response|RedirectResponse
    {
        $aspect = AssessmentAspect::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_score' => 'required|numeric|min:1|max:5',
        ]);

        $aspect->update($validated);

        return $this->hxOrRedirect($request, 'Aspek Penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified aspect.
     */
    public function destroyAspect(Request $request, int|string $id): Response|RedirectResponse
    {
        $aspect = AssessmentAspect::findOrFail($id);
        $aspect->delete();

        return $this->hxOrRedirect($request, 'Aspek Penilaian berhasil dihapus.');
    }
}
