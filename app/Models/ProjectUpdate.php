<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UpdateStatusType;
use Database\Factories\ProjectUpdateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tek bir zaman çizelgesi adımı.
 *
 * is_public: müşteri ekranı yalnızca true kayıtları görür.
 * Admin tüm kayıtları görür. Bu ayrım "iç not" ihtiyacını ekstra tablo olmadan çözer.
 *
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property string|null $description
 * @property UpdateStatusType $status_type
 * @property bool $is_public
 */
#[Fillable(['project_id', 'title', 'description', 'status_type', 'is_public'])]
class ProjectUpdate extends Model
{
    /** @use HasFactory<ProjectUpdateFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_type' => UpdateStatusType::class,
            'is_public' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
