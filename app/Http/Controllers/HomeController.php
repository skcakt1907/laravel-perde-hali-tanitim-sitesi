<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'featured'     => Product::active()->where('featured', true)->orderBy('sira')->take(8)->get(),
            'categories'   => Category::active()->whereNull('parent_id')->orderBy('sira')->get(),
            'services'     => Service::active()->orderBy('sira')->take(6)->get(),
            'projects'     => Project::active()->orderByDesc('featured')->orderBy('sira')->take(6)->get(),
            'posts'        => Post::active()->latest('tarih')->take(3)->get(),
            'testimonials' => Testimonial::active()->latest()->take(3)->get(),
        ]);
    }
}
