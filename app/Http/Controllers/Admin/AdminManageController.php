<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminManagePasswordRequest;
use App\Models\AuthSetting;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminManageController extends Controller
{
    public function index(): View
    {
        $admin = auth()->user();
        $setting = AuthSetting::current();

        return view('admin.admin-manage.index', [
            'admin' => $admin,
            'setting' => $setting,
        ]);
    }

    public function updatePassword(AdminManagePasswordRequest $request, AuditLogger $audit): RedirectResponse
    {
        $admin = auth()->user();
        $admin->update(['password' => $request->password]);
        $audit->recordAction('admin_password_changed');

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
