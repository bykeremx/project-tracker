<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;

final class CreateClientAction
{
    /**
     * @param  array{name: string, email?: string|null, company_name?: string|null}  $data
     */
    public function execute(array $data): Client
    {
        return Client::query()->create($data);
    }
}
