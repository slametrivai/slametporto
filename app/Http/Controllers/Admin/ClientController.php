<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Client::select(['id', 'name', 'industry', 'logo', 'website_url', 'is_active', 'sort_order']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('logo', function ($row) {
                    return '<div class="flex h-11 w-20 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-1">
                                <img src="'.e($row->logo_url).'" alt="'.e($row->name).'" class="max-h-full max-w-full object-contain">
                            </div>';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active
                        ? '<span class="admin-badge admin-badge-success">Tampil</span>'
                        : '<span class="admin-badge admin-badge-gray">Disembunyikan</span>';
                })
                ->editColumn('industry', function ($row) {
                    return '<span class="admin-badge admin-badge-brand">'.e($row->industry).'</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->name,
                        'editModal' => $row->only(['id', 'name', 'industry', 'website_url', 'is_active', 'sort_order']) + ['logo_url' => $row->logo_url],
                    ])->render();
                })
                ->rawColumns(['logo', 'industry', 'is_active', 'action'])
                ->make(true);
        }

        // Category names plus any industry already on a client, so editing never drops a value.
        $industries = Category::forClients()->pluck('name')
            ->merge(Client::query()->distinct()->pluck('industry'))
            ->filter()->unique()->sort()->values();

        return view('admin.clients.index', [
            'industries' => $industries->isEmpty() ? collect(['General Ecosystem']) : $industries,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'industry' => 'required|string|max:100',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $logoPath = $request->file('logo')->store('clients', 'public');

        Client::create([
            'name' => $validated['name'],
            'industry' => $validated['industry'],
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'is_active' => $request->boolean('is_active'), // unchecked boxes are not sent, so no default

            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.clients.index')->with('success', 'Klien baru berhasil ditambahkan!');
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'industry' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $validated['name'],
            'industry' => $validated['industry'],
            'website_url' => $validated['website_url'] ?? null,
            'is_active' => $request->boolean('is_active', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http')) {
                Storage::disk('public')->delete($client->logo);
            }
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'Data klien berhasil diperbarui!');
    }

    public function destroy(Client $client): JsonResponse|RedirectResponse
    {
        if ($client->logo && !str_starts_with($client->logo, 'http')) {
            Storage::disk('public')->delete($client->logo);
        }

        $client->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Klien berhasil dihapus!']);
        }

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil dihapus!');
    }
}
