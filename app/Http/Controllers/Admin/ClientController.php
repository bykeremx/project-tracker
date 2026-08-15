<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Client\CreateClientAction;
use App\Actions\Client\DeleteClientAction;
use App\Actions\Client\UpdateClientAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::query()
            ->with(['projects' => fn ($query) => $query->latest()->select('id', 'client_id', 'title', 'status', 'created_at')])
            ->withCount('projects')
            ->withSum('projects', 'agreed_budget')
            ->withSum('payments', 'amount')
            ->latest()
            ->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request, CreateClientAction $createClient): RedirectResponse
    {
        $this->authorize('create', Client::class);

        $createClient->execute($request->validated());

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Müşteri oluşturuldu.');
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(
        UpdateClientRequest $request,
        Client $client,
        UpdateClientAction $updateClient,
    ): RedirectResponse {
        $this->authorize('update', $client);

        $updateClient->execute($client, $request->validated());

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Müşteri güncellendi.');
    }

    public function destroy(Client $client, DeleteClientAction $deleteClient): RedirectResponse
    {
        $this->authorize('delete', $client);

        $deleteClient->execute($client);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Müşteri ve bağlı projeleri silindi.');
    }
}
