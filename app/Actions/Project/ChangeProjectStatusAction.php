<?php

declare(strict_types=1);

namespace App\Actions\Project;

use App\Enums\ProjectStatus;
use App\Models\Project;

/**
 * Tamamlandı işaretlenince gerçek bitiş tarihi otomatik yazılır.
 * Tekrar tamamlandıya çekilirse mevcut tarih korunur (geçmişi ezmeyiz).
 */
final class ChangeProjectStatusAction
{
    public function execute(Project $project, ProjectStatus $status): Project
    {
        $project->status = $status;

        if ($status === ProjectStatus::Completed && $project->actual_completion_date === null) {
            $project->actual_completion_date = now()->toDateString();
        }

        if ($status !== ProjectStatus::Completed) {
            $project->actual_completion_date = null;
        }

        $project->save();

        return $project;
    }
}
