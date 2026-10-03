<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CareerController extends Controller
{
    private const RULES = [
        'period' => 'required|string|max:100',
        'role' => 'required|string|max:150',
        'company' => 'required|string|max:150',
        'description' => 'nullable|string',
    ];

    public function index(): View
    {
        $careers = Career::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.careers.index', compact('careers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(self::RULES);

        // New entries go to the end; the order is changed by dragging on the index page.
        Career::create($validated + ['sort_order' => (int) Career::max('sort_order') + 1]);

        return redirect()->route('admin.careers.index')->with('success', 'Riwayat karier berhasil ditambahkan!');
    }

    public function update(Request $request, Career $career): RedirectResponse
    {
        $career->update($request->validate(self::RULES));

        return redirect()->route('admin.careers.index')->with('success', 'Riwayat karier berhasil diperbarui!');
    }

    public function reorder(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|distinct|exists:careers,id',
        ])['ids'];

        DB::transaction(function () use ($ids) {
            foreach ($ids as $position => $id) {
                Career::whereKey($id)->update(['sort_order' => $position + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan karier tersimpan.']);
    }

    public function destroy(Career $career): JsonResponse|RedirectResponse
    {
        $career->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Riwayat karier berhasil dihapus!']);
        }

        return redirect()->route('admin.careers.index')->with('success', 'Riwayat karier berhasil dihapus!');
    }
}
