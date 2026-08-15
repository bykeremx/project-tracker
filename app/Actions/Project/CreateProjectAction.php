<?php

declare(strict_types=1);

namespace App\Actions\Project;

use App\Enums\ProjectStatus;
use App\Models\Project;

/**
 * Yeni projede access_token burada üretilir; HTTP katmanı token görmez.
 * Böylece token asla formdan gelmez ve tahmin edilebilir değer yazılamaz.
 */
final class CreateProjectAction
{
    public function __construct(
        private readonly GenerateAccessTokenAction $generateAccessToken,
    ) {}

    /**
     * @param  array{client_id: int, title: string, start_date: string, target_completion_date: string, agreed_budget?: string|null}  $data
     */
    public function execute(array $data): Project
    {
        $project = new Project([
            'client_id' => $data['client_id'],
            'title' => $data['title'],
            'status' => ProjectStatus::InProgress,
            'start_date' => $data['start_date'],
            'target_completion_date' => $data['target_completion_date'],
            'agreed_budget' => $data['agreed_budget'] ?? null,
        ]);

        // access_token fillable değildir; yalnızca burada yazılır.
        $project->access_token = $this->generateAccessToken->execute();
        $project->save();

        return $project;
    }
}
