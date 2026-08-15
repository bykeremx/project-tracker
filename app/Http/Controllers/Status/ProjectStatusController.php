<?php

declare(strict_types=1);

namespace App\Http\Controllers\Status;

use App\Actions\Project\FindPublicProjectAction;
use App\Http\Controllers\Controller;
use App\Services\ProjectTimelineService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Müşteri ekranı: kimlik doğrulama yok, yazma yok.
 * Gizli notlar (is_public=false) sorguya hiç girmez.
 */
class ProjectStatusController extends Controller
{
    public function show(
        string $access_token,
        FindPublicProjectAction $findPublicProject,
        ProjectTimelineService $timeline,
    ): View {
        $project = $findPublicProject->execute($access_token);
        $updates = $timeline->paginate($project, publicOnly: true);

        return view('status.show', compact('project', 'updates'));
    }

    public function updates(
        string $access_token,
        FindPublicProjectAction $findPublicProject,
        ProjectTimelineService $timeline,
    ): JsonResponse {
        $project = $findPublicProject->execute($access_token);
        $updates = $timeline->paginate($project, publicOnly: true);

        return response()->json([
            'html' => view('status.partials.cards', ['updates' => $updates])->render(),
            'next_cursor' => $updates->nextCursor()?->encode(),
            'has_more' => $updates->hasMorePages(),
        ]);
    }
}
