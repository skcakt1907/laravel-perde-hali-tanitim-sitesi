<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

/** Müşteri yorumları (anasayfa + hakkımızda) */
class TestimonialController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial(['durum' => true, 'stars' => 5]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['photo'] = $this->resolveImage($request, 'image_file', $data['photo'] ?? null, 'testimonials');

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Yorum eklendi.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->payload($request);
        $data['photo'] = $this->resolveImage($request, 'image_file', $data['photo'] ?? null, 'testimonials');

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Yorum güncellendi.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Yorum silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate(Testimonial::translationRules([
            'title'   => 'nullable|string|max:120',
            'comment' => 'required|string|max:1000',
        ]) + [
            'name'       => 'required|string|max:120',
            'stars'      => 'required|integer|min:1|max:5',
            'photo'      => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['durum'] = $request->boolean('durum');

        unset($data['image_file']);

        return $data;
    }
}
