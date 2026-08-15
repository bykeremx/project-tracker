<?php

declare(strict_types=1);

namespace App\Actions\Project;

use App\Models\Project;

/**
 * Müşteri ekranı için salt-okunur proje yükleme.
 *
 * Sorgu: WHERE access_token = ? LIMIT 1
 * Index: idx_access_token UNIQUE → const/ref lookup, full scan yok.
 *
 * client ilişkisi eager load edilir (N+1 yok).
 * agreed_budget ve payments kasıtlı olarak seçilmez; müşteri parayı görmez.
 * Gizli güncellemeler burada çekilmez; timeline ayrı serviste publicOnly=true ile gelir.
 */
final class FindPublicProjectAction
{
    public function execute(string $accessToken): Project
    {
        return Project::query()
            ->select([
                'id',
                'client_id',
                'title',
                'access_token',
                'status',
                'start_date',
                'target_completion_date',
                'actual_completion_date',
            ])
            ->with(['client:id,name,company_name'])
            ->where('access_token', $accessToken)
            ->firstOrFail();
    }
}
