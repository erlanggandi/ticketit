<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiSettingRequest;
use App\Models\AiSetting;
use App\Services\AiService;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiSettingController extends Controller
{
    public function index(): View
    {
        return $this->show();
    }

    public function show(): View
    {
        $setting = AiSetting::currentOrCreate();
        $aiService = app(AiService::class);

        return view('admin.ai-settings.show', [
            'setting' => $setting,
            'providers' => $aiService->providers(),
            'aiProviders' => $aiService->providers(),
        ]);
    }

    public function edit(): View
    {
        return $this->show();
    }

    public function update(AiSettingRequest $request, AuditLogger $audit): RedirectResponse
    {
        $setting = AiSetting::query()->firstOrNew();
        $validated = $request->validated();

        $old = $setting->exists ? $setting->only(array_keys($setting->getFillable())) : null;

        $setting->fill([
            'provider' => $validated['provider'],
            'api_key' => $validated['api_key'] ?? $setting->api_key,
            'base_url' => $validated['base_url'] ?? $setting->base_url,
            'model' => $validated['model'] ?? $setting->model,
            'temperature' => $validated['temperature'] ?? $setting->temperature,
            'max_tokens' => $validated['max_tokens'] ?? $setting->max_tokens,
            'updated_by' => $request->user()?->getAuthIdentifier(),
        ]);

        $setting->save();

        $audit->record($setting, 'updated', $old, ['ai_settings_saved' => true]);

        return back()->with('success', 'Pengaturan AI berhasil disimpan.');
    }

    public function test(Request $request, AiService $aiService): JsonResponse
    {
        $setting = AiSetting::query()->firstOrNew();

        foreach (['provider', 'api_key', 'base_url', 'model', 'temperature', 'max_tokens'] as $field) {
            $value = $request->input($field);

            if ($value !== null && $value !== '') {
                $setting->{$field} = $value;
            }
        }

        $result = $aiService->testConnection($setting);

        return response()->json($result);
    }
}
