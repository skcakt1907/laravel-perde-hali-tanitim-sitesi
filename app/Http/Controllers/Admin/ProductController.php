<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('sira')->latest();

        if ($request->filled('q')) {
            $query->where(fn ($q) => $q->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('name_tr', 'like', '%' . $request->q . '%'));
        }
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        return view('admin.products.index', [
            'products'   => $query->paginate(20)->withQueryString(),
            'categories' => Category::orderBy('sira')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'product'    => new Product(['durum' => true, 'price' => 0]),
            'categories' => Category::orderBy('sira')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['slug']   = $this->uniqueSlug(Product::class, $data['name']);
        $data['cover']  = $this->resolveImage($request, 'image_file', $data['cover'] ?? null);
        $data['images'] = $this->resolveGallery($request, 'gallery_files', []);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Ürün eklendi.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product'    => $product,
            'categories' => Category::orderBy('sira')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->payload($request);

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug(Product::class, $data['name'], $product->id);
        }

        $data['cover']  = $this->resolveImage($request, 'image_file', $data['cover'] ?? $product->cover);
        $data['images'] = $this->resolveGallery($request, 'gallery_files', $request->input('keep_images', []));

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Ürün güncellendi.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Ürün silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate([
            'name'            => 'required|string|max:200',
            'name_tr'         => 'nullable|string|max:200',
            'category_id'     => 'nullable|exists:categories,id',
            'brand'           => 'nullable|string|max:100',
            'sku'             => 'nullable|string|max:60',
            'cover'           => 'nullable|string|max:500',
            'short_desc'      => 'nullable|string|max:500',
            'short_desc_tr'   => 'nullable|string|max:500',
            'description'     => 'nullable|string',
            'description_tr'  => 'nullable|string',
            'price'           => 'nullable|numeric|min:0',
            'price_unit'      => 'nullable|string|max:30',
            'sira'            => 'nullable|integer|min:0',
            'image_file'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'gallery_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['price']      = $data['price'] ?? 0;
        $data['sira']       = $data['sira'] ?? 0;
        $data['featured']   = $request->boolean('featured');
        $data['durum']      = $request->boolean('durum');
        $data['attributes'] = $this->parseAttributes($request->input('attributes_raw'));

        unset($data['image_file'], $data['gallery_files']);

        return $data;
    }
}
