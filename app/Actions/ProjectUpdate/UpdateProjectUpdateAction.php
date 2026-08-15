<?php

declare(strict_types=1);

namespace App\Actions\ProjectUpdate;

use App\Models\ProjectUpdate;

/**
 * Yalnızca görünürlük ve durum tipi değişir.
 * Başlık/açıklama zaman çizelgesi geçmişini korumak için burada dokunulmaz.
 */
final class UpdateProjectUpdateAction
{
    /**
     * @param  array{status_type: string, is_public?: bool}  $data
     */
    public function execute(ProjectUpdate $update, array $data): ProjectUpdate
    {
        $update->update([
            'status_type' => $data['status_type'],
            'is_public' => $data['is_public'] ?? false,
        ]);

        return $update;
    }
}
