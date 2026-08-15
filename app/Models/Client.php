<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Müşteri modeli.
 *
 * Neden ayrı tablo/model?
 * Bir müşterinin birden fazla projesi olabilir. Müşteri bilgisi projeye gömülmez;
 * böylece isim/e-posta güncellemesi tüm projelere yansır (normalization).
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $company_name
 */
#[Fillable(['name', 'email', 'company_name'])]
class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return HasManyThrough<Payment, Project, $this>
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Project::class);
    }
}
