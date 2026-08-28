<?php

use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthSettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SlaSettingController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\TelegramSettingController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Api\EmployeeSearchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\TicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tickets/create')->name('home');
Route::redirect('/admin', '/admin/dashboard')->name('admin');

Route::middleware(['throttle:60,1', 'share.site-setting'])->group(function () {
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/tickets/check', [TicketController::class, 'check'])->name('tickets.check');
    Route::get('/tickets/{ticket}/success', [TicketController::class, 'success'])->name('tickets.success');

    Route::get('/api/employees/search', EmployeeSearchController::class)->name('api.employees.search');
});

Route::middleware('throttle:10,1')->post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.attempt');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/export', [AdminTicketController::class, 'export'])->name('tickets.export');
        Route::post('/tickets/import', [AdminTicketController::class, 'import'])->name('tickets.import');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::patch('/tickets/{ticket}/details', [AdminTicketController::class, 'updateDetails'])->name('tickets.updateDetails');
        Route::patch('/tickets/{ticket}/assignment', [AdminTicketController::class, 'assign'])->name('tickets.assign');

        Route::resource('categories', CategoryController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['categories' => 'category']);
        Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');

        Route::resource('employees', EmployeeController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');
        Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
        Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');

        Route::resource('technicians', TechnicianController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['technicians' => 'technician']);
        Route::patch('/technicians/{technician}/toggle', [TechnicianController::class, 'toggle'])->name('technicians.toggle');

        Route::get('/sla-settings', [SlaSettingController::class, 'edit'])->name('sla-settings.edit');
        Route::put('/sla-settings', [SlaSettingController::class, 'update'])->name('sla-settings.update');

        Route::get('/telegram-settings', [TelegramSettingController::class, 'show'])->name('telegram-settings.show');
        Route::put('/telegram-settings', [TelegramSettingController::class, 'update'])->name('telegram-settings.update');
        Route::post('/telegram-settings/test', [TelegramSettingController::class, 'test'])->name('telegram-settings.test');

        Route::get('/site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('/site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

        Route::get('/auth-settings', [AuthSettingController::class, 'show'])->name('auth-settings.show');
        Route::put('/auth-settings', [AuthSettingController::class, 'update'])->name('auth-settings.update');
        Route::post('/auth-settings/test', [AuthSettingController::class, 'test'])->name('auth-settings.test');

        Route::get('/admin-manage', [AdminManageController::class, 'index'])->name('admin-manage.index');
        Route::put('/admin-manage/password', [AdminManageController::class, 'updatePassword'])->name('admin-manage.updatePassword');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
