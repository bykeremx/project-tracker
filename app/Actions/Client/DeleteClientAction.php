<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;

/**
 * Silme CASCADE ile projeleri ve güncellemeleri de kaldırır.
 * Bu kural veritabanı FK'sinde tanımlıdır; uygulama katmanında ek döngü gerekmez.
 */
final class DeleteClientAction
{
    public function execute(Client $client): void
    {
        $client->delete();
    }
}
