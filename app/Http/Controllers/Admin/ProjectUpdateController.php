<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\ProjectUpdate\CreateProjectUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectUpdate\StoreProjectUpdateRequest;
use App\Models\Project;
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
}
