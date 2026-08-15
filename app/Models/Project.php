<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Proje modeli.
 *
 * access_token fillable değildir: istekten gelmez, Action katmanında üretilir.
 * Aksi halde istemci kendi token'ını seçebilir ve tahmin edilebilirlik artar.
 *
 * @property int $id
 * @property int $client_id
 * @property string $title
 * @property string $access_token
 * @property ProjectStatus $status
 * @property Carbon $start_date
 * @property Carbon $target_completion_date
 * @property Carbon|null $actual_completion_date
 */
#[Fillable([
    'client_id',
    'title',
    'status',
    'start_date',
    'target_completion_date',
    'actual_completion_date',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'start_date' => 'date',
            'target_completion_date' => 'date',
            'actual_completion_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany<ProjectUpdate, $this>
     */
    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function publicStatusUrl(): string
    {
        return route('status.show', $this->access_token);
    }
}
