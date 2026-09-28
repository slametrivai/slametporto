<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class InquiryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Inquiry::select(['id', 'name', 'email', 'subject', 'message', 'status', 'created_at']);

            if ($request->filled('status_filter') && $request->status_filter !== 'all') {
                $data->where('status', $request->status_filter);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y, H:i');
                })
                ->editColumn('name', function ($row) {
                    return '<span class="font-medium text-gray-800">'.e($row->name).'</span>';
                })
                ->editColumn('email', function ($row) {
                    return '<a href="mailto:'.e($row->email).'" class="admin-link">'.e($row->email).'</a>';
                })
                ->editColumn('status', function ($row) {
                    return match ($row->status) {
                        'new' => '<span class="admin-badge admin-badge-error">Baru</span>',
                        'read' => '<span class="admin-badge admin-badge-warning">Dibaca</span>',
                        'responded' => '<span class="admin-badge admin-badge-success">Dibalas</span>',
                        default => '<span class="admin-badge admin-badge-gray">'.e($row->status).'</span>',
                    };
                })
                ->addColumn('action', function ($row) {
                    $mail = 'mailto:'.$row->email.'?'.http_build_query([
                        'subject' => "Re: {$row->subject}",
                        'body' => "Halo {$row->name},\n\nTerima kasih telah menghubungi saya.\n\nSalam,\n".setting('site_author', 'Slamet Rivai'),
                    ], '', '&', PHP_QUERY_RFC3986);

                    return view('admin.partials.row-actions', [
                        'id' => $row->id,
                        'name' => $row->name,
                        'detail' => true,
                        'mail' => $mail,
                    ])->render();
                })
                ->rawColumns(['name', 'email', 'status', 'action'])
                ->make(true);
        }

        return view('admin.inquiries.index');
    }

    public function show(Inquiry $inquiry): JsonResponse
    {
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $inquiry->id,
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'subject' => $inquiry->subject,
                'message' => nl2br(e($inquiry->message)),
                'status' => $inquiry->status,
                'created_at' => $inquiry->created_at->format('d M Y, H:i:s'),
            ]
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,responded',
        ]);

        $inquiry->update(['status' => $validated['status']]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status pesan berhasil diubah!']);
        }

        return back()->with('success', 'Status pesan berhasil diubah!');
    }

    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status', 'all');

        $query = Inquiry::latest();
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $inquiries = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inquiries_export_'.date('Ymd_His').'.csv"',
        ];

        return response()->stream(function () use ($inquiries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama', 'Email', 'Subjek', 'Pesan', 'Status', 'Tanggal Masuk']);

            // Public form input: prefix cells a spreadsheet would evaluate as a formula.
            $safe = fn ($value) => is_string($value) && preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;

            foreach ($inquiries as $row) {
                fputcsv($file, array_map($safe, [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->subject,
                    $row->message,
                    $row->status,
                    $row->created_at->format('Y-m-d H:i:s'),
                ]));
            }

            fclose($file);
        }, 200, $headers);
    }

    public function destroy(Inquiry $inquiry): JsonResponse|RedirectResponse
    {
        $inquiry->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Pesan berhasil dihapus!']);
        }

        return redirect()->route('admin.inquiries.index')->with('success', 'Pesan berhasil dihapus!');
    }
}
