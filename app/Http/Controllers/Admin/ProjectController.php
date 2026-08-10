<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

/** Yapılan işler / galeri yönetimi */
class ProjectController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.projects.index', [
            'projects' => Project::orderBy('sira')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project(['durum' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['slug']   = $this->uniqueSlug(Project::class, $data['title']);
        $data['cover']  = $this->resolveImage($request, 'image_file', $data['cover'] ?? null, 'projects');
        $data['images'] = $this->resolveGallery($request, 'gallery_files', [], 'projects');

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proje eklendi.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->payload($request);

        if ($data['title'] !== $project->title) {
            $data['slug'] = $this->uniqueSlug(Project::class, $data['title'], $project->id);
        }

        $data['cover']  = $this->resolveImage($request, 'image_file', $data['cover'] ?? $project->cover, 'projects');
        $data['images'] = $this->resolveGallery($request, 'gallery_files', $request->input('keep_images', []), 'projects');

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proje güncellendi.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('success', 'Proje silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate(Project::translationRules([
            'title'   => 'required|string|max:200',
            'kind'    => 'nullable|string|max:80',
            'summary' => 'nullable|string|max:500',
            'content' => 'nullable|string',
        ]) + [
            'location'        => 'nullable|string|max:120',
            'cover'           => 'nullable|string|max:500',
            'tarih'           => 'nullable|date',
            'sira'            => 'nullable|integer|min:0',
            'image_file'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'gallery_files.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['sira']     = $data['sira'] ?? 0;
        $data['featured'] = $request->boolean('featured');
        $data['durum']    = $request->boolean('durum');

        unset($data['image_file'], $data['gallery_files']);

        return $data;
    }
}
