<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CertificationController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Certification::select(['id', 'title', 'issuer', 'score', 'valid_period', 'credential_url']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('title', function ($row) {
                    return '<span class="font-medium text-gray-800">'.e($row->title).'</span>';
                })
                ->editColumn('score', function ($row) {
                    return $row->score
                        ? '<span class="admin-badge admin-badge-success">'.e($row->score).'</span>'
                        : 'Tidak ada';
                })
                ->editColumn('credential_url', function ($row) {
                    return $row->credential_url
                        ? '<a href="'.e($row->credential_url).'" target="_blank" rel="noopener" class="admin-link">Buka verifikasi<span class="sr-only"> (tab baru)</span></a>'
                        : 'Tidak ada';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->title,
                        'edit' => route('admin.certifications.edit', $row->id),
                    ])->render();
                })
                ->rawColumns(['title', 'score', 'credential_url', 'action'])
                ->make(true);
        }

        return view('admin.certifications.index');
    }

    public function create(): View
    {
        return view('admin.certifications.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:150',
            'score' => 'nullable|string|max:50',
            'valid_period' => 'nullable|string|max:100',
            'credential_url' => 'nullable|url|max:255',
        ]);

        Certification::create($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Sertifikasi kompetensi berhasil ditambahkan!');
    }

    public function edit(Certification $certification): View
    {
        return view('admin.certifications.edit', compact('certification'));
    }

    public function update(Request $request, Certification $certification): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:150',
            'score' => 'nullable|string|max:50',
            'valid_period' => 'nullable|string|max:100',
            'credential_url' => 'nullable|url|max:255',
        ]);

        $certification->update($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Sertifikasi kompetensi berhasil diperbarui!');
    }

    public function destroy(Certification $certification): JsonResponse|RedirectResponse
    {
        $certification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Sertifikasi berhasil dihapus!']);
        }

        return redirect()->route('admin.certifications.index')->with('success', 'Sertifikasi berhasil dihapus!');
    }
}
