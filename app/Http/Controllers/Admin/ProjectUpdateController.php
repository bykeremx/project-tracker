<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\ProjectUpdate\CreateProjectUpdateAction;
use App\Actions\ProjectUpdate\UpdateProjectUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectUpdate\StoreProjectUpdateRequest;
use App\Http\Requests\ProjectUpdate\UpdateProjectUpdateRequest;
use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Http\RedirectResponse;

class ProjectUpdateController extends Controller
{
    public function store(
        StoreProjectUpdateRequest $request,
        Project $project,
        CreateProjectUpdateAction $createUpdate,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $createUpdate->execute($project, $request->validated());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Güncelleme eklendi.');
    }

    public function update(
        UpdateProjectUpdateRequest $request,
        Project $project,
        ProjectUpdate $update,
        UpdateProjectUpdateAction $updateAction,
    ): RedirectResponse {
        $this->authorize('update', $project);

        abort_unless($update->project_id === $project->id, 404);

        $updateAction->execute($update, $request->validated());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Güncelleme ayarları kaydedildi.');
    }
}
