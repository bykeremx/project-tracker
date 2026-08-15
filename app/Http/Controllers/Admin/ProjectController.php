<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Project\ChangeProjectStatusAction;
use App\Actions\Project\CreateProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\UpdateProjectStatusRequest;
use App\Models\Client;
use App\Models\Project;
use App\Services\ProjectTimelineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::query()
            ->with('client:id,name,company_name')
            ->withCount('updates')
            ->withSum('payments', 'amount')
            ->when($request->integer('client_id'), fn ($query, $clientId) => $query->where('client_id', $clientId))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('admin.projects.create', [
            'clients' => Client::query()->orderBy('name')->get(['id', 'name', 'company_name']),
        ]);
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $createProject): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $project = $createProject->execute($request->validated());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Proje oluşturuldu. Canlı bağlantı hazır.');
    }

    public function show(Project $project, ProjectTimelineService $timeline): View
    {
        $this->authorize('view', $project);

        $project->load([
            'client',
            'payments' => fn ($query) => $query->latest('paid_on')->latest('id'),
        ]);

        return view('admin.projects.show', [
            'project' => $project,
            'updates' => $timeline->paginate($project, publicOnly: false),
            'statuses' => ProjectStatus::cases(),
        ]);
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('admin.projects.edit', [
            'project' => $project,
            'clients' => Client::query()->orderBy('name')->get(['id', 'name', 'company_name']),
        ]);
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project,
        UpdateProjectAction $updateProject,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $updateProject->execute($project, $request->validated());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Proje güncellendi.');
    }

    public function updateStatus(
        UpdateProjectStatusRequest $request,
        Project $project,
        ChangeProjectStatusAction $changeStatus,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $status = ProjectStatus::from($request->validated('status'));
        $changeStatus->execute($project, $status);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Proje durumu güncellendi.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proje silindi.');
    }
}
