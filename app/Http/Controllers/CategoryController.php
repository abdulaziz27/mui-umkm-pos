<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->tenant->categories()->latest()->paginate(10);
        return view('menu.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('menu.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        
        auth()->user()->tenant->categories()->create($validated);
        
        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        if ($category->tenant_id !== auth()->user()->tenant_id) abort(403);
        return view('menu.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if ($category->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        
        $category->update($validated);
        
        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        if ($category->products()->count() > 0) {
            return redirect()->route('menu.categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();
        
        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
