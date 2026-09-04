<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $kinds = Project::active()->whereNotNull('kind')->distinct()->orderBy('kind')->pluck('kind');
        $kind  = $request->query('art');

        $projects = Project::active()
            ->when($kind && $kinds->contains($kind), fn ($q) => $q->where('kind', $kind))
            ->orderByDesc('featured')->orderBy('sira')->paginate(12)->withQueryString();

        return view('projects.index', compact('projects', 'kinds', 'kind'));
    }

    public function show(Project $project)
    {
        // Pasife alinmis icerik: 404 yerine liste sayfasi (bkz. PasifIcerik)
        if (! $project->durum) {
            return \App\Support\PasifIcerik::listeyeGonder('gallery');
        }

        return view('projects.show', [
            'project' => $project,
            'others'  => Project::active()->where('id', '<>', $project->id)
                ->orderBy('sira')->take(4)->get(),
        ]);
    }
}
