<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the site settings & SEO configuration form.
     */
    public function index(): View
    {
        $settings = Setting::getAll();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the site settings, logo, favicon, and SEO configuration.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:150',
            'site_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'site_author' => 'nullable|string|max:100',
            'canonical_url' => 'nullable|url|max:255',
            'twitter_handle' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:150',
            'whatsapp_number' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+\s\-()]+$/'],
            'linkedin_url' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'site_favicon' => 'nullable|file|mimes:ico,png,svg|max:1024',
        ]);

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath);
        } elseif ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('site_logo', null);
        }

        // Handle Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath);
        } elseif ($request->boolean('remove_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::set('site_favicon', null);
        }

        // wa.me only accepts international digits, so "0812-..." becomes "62812...".
        if (!empty($validated['whatsapp_number'])) {
            $digits = preg_replace('/\D/', '', $validated['whatsapp_number']);
            $validated['whatsapp_number'] = str_starts_with($digits, '0') ? '62'.substr($digits, 1) : $digits;
        }

        // Update Text & SEO Settings
        $textKeys = [
            'site_name',
            'site_tagline',
            'site_title',
            'meta_description',
            'meta_keywords',
            'site_author',
            'canonical_url',
            'twitter_handle',
            'contact_email',
            'whatsapp_number',
            'linkedin_url',
        ];

        foreach ($textKeys as $key) {
            Setting::set($key, $validated[$key] ?? null);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan identitas website & SEO berhasil diperbarui.');
    }
}
