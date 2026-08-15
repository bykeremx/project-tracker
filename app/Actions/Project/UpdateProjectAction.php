<?php

declare(strict_types=1);

namespace App\Actions\Project;

use App\Models\Project;

/**
 * Token ve durum bu action'da değişmez.
 * Token kalıcıdır (müşteri linki kırılmasın). Durum ayrı bir action'dadır.
 */
final class UpdateProjectAction
{
    /**
     * @param  array{client_id: int, title: string, start_date: string, target_completion_date: string}  $data
     */
    public function execute(Project $project, array $data): Project
    {
        $project->update([
            'client_id' => $data['client_id'],
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'target_completion_date' => $data['target_completion_date'],
        ]);

        return $project;
    }
}
