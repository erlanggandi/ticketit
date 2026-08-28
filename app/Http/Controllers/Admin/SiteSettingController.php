<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $setting = SiteSetting::currentOrCreate();

        return view('admin.site-settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(SiteSettingRequest $request, AuditLogger $audit): RedirectResponse
    {
        $setting = SiteSetting::query()->firstOrNew();
        $old = $setting->exists ? $setting->only(SiteSetting::FIELDS) : null;
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('', 'public');
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon_path'] = $request->file('favicon')->store('', 'public');
        }

        $validated['updated_by'] = $request->user()?->getAuthIdentifier();
        $setting->fill($validated)->save();
        $audit->record($setting, 'updated', $old, ['site_settings_saved' => true]);

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
