<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Contracts\Pagination\CursorPaginator;

/**
 * Timeline sorgularını tek yerde toplar.
 *
 * Neden cursorPaginate?
 * OFFSET n LIMIT 20 büyük n'de n satırı atlamak zorundadır (O(n)).
 * Cursor (id < son_id) index seek yapar; RAM ve I/O sabit kalır.
 * Infinite scroll tam olarak bu modele oturur.
 *
 * Index: idx_project_created (project_id, id DESC) — admin
 * Index: idx_project_public_created (project_id, is_public, id DESC) — müşteri
 * EXPLAIN (MySQL): type=ref, Extra=Backward index scan
 * Full table scan olmaz. N+1 yoktur: tek proje, tek ilişki sorgusu.
 */
final class ProjectTimelineService
{
    /**
     * @return CursorPaginator<int, ProjectUpdate>
     */
    public function paginate(Project $project, bool $publicOnly = false, int $perPage = 20): CursorPaginator
    {
        $query = $project->updates()->orderByDesc('id');

        if ($publicOnly) {
            $query->where('is_public', true);
        }

        return $query->cursorPaginate($perPage);
    }
}
