<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.services.index', [
            'services' => Service::orderBy('sira')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['durum' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['slug']  = $this->uniqueSlug(Service::class, $data['title']);
        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? null, 'services');

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Hizmet eklendi.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->payload($request);

        if ($data['title'] !== $service->title) {
            $data['slug'] = $this->uniqueSlug(Service::class, $data['title'], $service->id);
        }

        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? $service->image, 'services');

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Hizmet güncellendi.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Hizmet silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'title_tr'   => 'nullable|string|max:200',
            'icon'       => 'nullable|string|max:60',
            'image'      => 'nullable|string|max:500',
            'summary'    => 'nullable|string|max:500',
            'summary_tr' => 'nullable|string|max:500',
            'content'    => 'nullable|string',
            'content_tr' => 'nullable|string',
            'sira'       => 'nullable|integer|min:0',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['sira']  = $data['sira'] ?? 0;
        $data['durum'] = $request->boolean('durum');

        unset($data['image_file']);

        return $data;
    }
}
