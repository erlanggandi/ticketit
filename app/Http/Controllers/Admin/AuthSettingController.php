<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthSettingRequest;
use App\Models\AuthSetting;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthSettingController extends Controller
{
    public function show(): View
    {
        $setting = AuthSetting::current();

        return view('admin.auth-settings.show', [
            'setting' => $setting,
            'fields' => AuthSetting::FIELDS,
        ]);
    }

    public function update(AuthSettingRequest $request, AuditLogger $audit): RedirectResponse
    {
        $setting = AuthSetting::query()->firstOrNew();
        $validated = $request->validated();

        $old = $setting->only(array_keys($setting->getFillable()));

        $setting->fill([
            ...$validated,
            'updated_by' => $request->user()?->getAuthIdentifier(),
        ]);

        $setting->save();
        $audit->record($setting, 'updated', $old, ['auth_settings_saved' => true]);

        return back()->with('success', 'Pengaturan akun berhasil disimpan.');
    }

    public function test(): JsonResponse
    {
        $setting = AuthSetting::current();

        return response()->json([
            'ok' => true,
            'message' => 'Pengaturan akun tersimpan.',
            'setting' => $setting ? $setting->only(AuthSetting::FIELDS) : [],
        ]);
    }
}
