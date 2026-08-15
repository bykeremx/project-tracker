<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;

final class UpdateClientAction
{
    /**
     * @param  array{name: string, email?: string|null, company_name?: string|null}  $data
     */
    public function execute(Client $client, array $data): Client
    {
        $client->update($data);

        return $client;
    }
}
