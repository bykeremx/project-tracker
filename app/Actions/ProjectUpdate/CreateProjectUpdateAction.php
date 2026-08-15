<?php

declare(strict_types=1);

namespace App\Actions\ProjectUpdate;

use App\Models\Project;
use App\Models\ProjectUpdate;

final class CreateProjectUpdateAction
{
    /**
     * @param  array{title: string, description?: string|null, status_type: string, is_public?: bool}  $data
     */
    public function execute(Project $project, array $data): ProjectUpdate
    {
        return $project->updates()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status_type' => $data['status_type'],
            'is_public' => $data['is_public'] ?? true,
        ]);
    }
}
