<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        return view('catalog.index', [
            'category'   => null,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sira')->get(),
            'products'   => Product::active()->with('category')->orderBy('sira')->paginate(12),
        ]);
    }

    public function category(Category $category)
    {
        abort_unless($category->durum, 404);

        // Alt kategoriler varsa onların ürünleri de listelenir
        $ids = $category->children()->pluck('id')->push($category->id);

        return view('catalog.index', [
            'category'   => $category,
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sira')->get(),
            'products'   => Product::active()->whereIn('category_id', $ids)
                ->with('category')->orderBy('sira')->paginate(12),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->durum, 404);

        return view('catalog.show', [
            'product' => $product->load('category'),
            'related' => Product::active()
                ->where('category_id', $product->category_id)
                ->where('id', '<>', $product->id)
                ->orderBy('sira')->take(4)->get(),
        ]);
    }
}
