<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('sira')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(['durum' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['slug']  = $this->uniqueSlug(Category::class, $data['name']);
        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? null, 'categories');

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori eklendi.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->payload($request);

        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->uniqueSlug(Category::class, $data['name'], $category->id);
        }

        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? $category->image, 'categories');

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate([
            'name'           => 'required|string|max:120',
            'name_tr'        => 'nullable|string|max:120',
            'icon'           => 'nullable|string|max:60',
            'image'          => 'nullable|string|max:500',
            'sira'           => 'nullable|integer|min:0',
            'description'    => 'nullable|string|max:500',
            'description_tr' => 'nullable|string|max:500',
            'image_file'     => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['sira']  = $data['sira'] ?? 0;
        $data['durum'] = $request->boolean('durum');

        unset($data['image_file']);

        return $data;
    }
}
