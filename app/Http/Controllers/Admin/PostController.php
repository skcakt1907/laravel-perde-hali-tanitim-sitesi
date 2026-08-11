<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

/** Rehber yazıları (ön yüzde /ratgeber) */
class PostController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::latest('tarih')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['durum' => true, 'tarih' => now()]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request);
        $data['slug']  = $this->uniqueSlug(Post::class, $data['title']);
        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? null, 'posts');

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Yazı eklendi.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->payload($request);

        if ($data['title'] !== $post->title) {
            $data['slug'] = $this->uniqueSlug(Post::class, $data['title'], $post->id);
        }

        $data['image'] = $this->resolveImage($request, 'image_file', $data['image'] ?? null, 'posts');

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Yazı güncellendi.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Yazı silindi.');
    }

    protected function payload(Request $request): array
    {
        $data = $request->validate(Post::translationRules([
            'title'    => 'required|string|max:200',
            'category' => 'nullable|string|max:80',
            'summary'  => 'nullable|string|max:500',
            'content'  => 'nullable|string',
        ]) + [
            'image'      => 'nullable|string|max:500',
            'tarih'      => 'nullable|date',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $data['durum'] = $request->boolean('durum');

        unset($data['image_file']);

        return $data;
    }
}
