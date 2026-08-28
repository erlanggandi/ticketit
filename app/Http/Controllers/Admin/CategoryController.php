<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->withCount('tickets')
                ->orderBy('category_name')
                ->get(),
        ]);
    }

    public function store(CategoryRequest $request, AuditLogger $audit): RedirectResponse
    {
        $category = Category::query()->create([
            'category_name' => $this->normalize($request->validated('category_name')),
            'is_active' => true,
        ]);

        $audit->record($category, 'created', null, ['category_name' => $category->category_name]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(CategoryRequest $request, Category $category, AuditLogger $audit): RedirectResponse
    {
        $old = ['category_name' => $category->category_name, 'is_active' => $category->is_active];

        $data = [
            'category_name' => $this->normalize($request->validated('category_name')),
            'is_active' => $request->boolean('is_active'),
        ];

        $category->update($data);
        $audit->record($category, 'updated', $old, $data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(Category $category, AuditLogger $audit): RedirectResponse
    {
        $old = ['is_active' => $category->is_active];
        $category->forceFill(['is_active' => ! $category->is_active])->save();

        $audit->record($category, 'toggled', $old, ['is_active' => $category->is_active]);

        return back()->with(
            'success',
            $category->is_active ? 'Kategori diaktifkan.' : 'Kategori dinonaktifkan.'
        );
    }

    public function destroy(Category $category, AuditLogger $audit): RedirectResponse
    {
        if ($category->tickets()->exists()) {
            return back()->withErrors([
                'delete' => 'Kategori sudah memiliki tiket terkait. Nonaktifkan saja kategorinya.',
            ]);
        }

        $audit->record($category, 'deleted', ['category_name' => $category->category_name], null);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function normalize(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', $name) ?? $name);
    }
}
