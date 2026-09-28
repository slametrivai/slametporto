<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CareerController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Career::select(['id', 'period', 'role', 'company', 'description', 'sort_order']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('company', function ($row) {
                    return '<span class="font-medium text-gray-800">'.e($row->company).'</span>';
                })
                ->editColumn('period', function ($row) {
                    return '<span class="admin-badge admin-badge-gray">'.e($row->period).'</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->company,
                        'edit' => route('admin.careers.edit', $row->id),
                    ])->render();
                })
                ->rawColumns(['company', 'period', 'action'])
                ->make(true);
        }

        return view('admin.careers.index');
    }

    public function create(): View
    {
        return view('admin.careers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period' => 'required|string|max:100',
            'role' => 'required|string|max:150',
            'company' => 'required|string|max:150',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Career::create([
            'period' => $validated['period'],
            'role' => $validated['role'],
            'company' => $validated['company'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.careers.index')->with('success', 'Riwayat karier berhasil ditambahkan!');
    }

    public function edit(Career $career): View
    {
        return view('admin.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career): RedirectResponse
    {
        $validated = $request->validate([
            'period' => 'required|string|max:100',
            'role' => 'required|string|max:150',
            'company' => 'required|string|max:150',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $career->update($validated);

        return redirect()->route('admin.careers.index')->with('success', 'Riwayat karier berhasil diperbarui!');
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
