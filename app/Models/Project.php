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
 * @property string|null $agreed_budget
 */
#[Fillable([
    'client_id',
    'title',
    'status',
    'start_date',
    'target_completion_date',
    'actual_completion_date',
    'agreed_budget',
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
            'agreed_budget' => 'decimal:2',
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

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function publicStatusUrl(): string
    {
        return route('status.show', $this->access_token);
    }

    /**
     * withSum veya yüklü ilişki varsa ekstra sorgu atılmaz.
     */
    public function collectedAmount(): string
    {
        if (array_key_exists('payments_sum_amount', $this->attributes)) {
            return $this->normalizeAmount($this->attributes['payments_sum_amount'] ?? 0);
        }

        if ($this->relationLoaded('payments')) {
            return $this->normalizeAmount($this->payments->sum('amount'));
        }

        return $this->normalizeAmount($this->payments()->sum('amount'));
    }

    public function remainingAmount(): ?string
    {
        if ($this->agreed_budget === null) {
            return null;
        }

        return $this->normalizeAmount((float) $this->agreed_budget - (float) $this->collectedAmount());
    }

    private function normalizeAmount(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
