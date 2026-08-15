<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\AdminUser\CreateAdminUserAction;
use App\Actions\AdminUser\DeleteAdminUserAction;
use App\Actions\AdminUser\UpdateAdminUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUser\StoreAdminUserRequest;
use App\Http\Requests\AdminUser\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $admins = User::query()
            ->latest('id')
            ->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.admins.create');
    }

    public function store(StoreAdminUserRequest $request, CreateAdminUserAction $createAdmin): RedirectResponse
    {
        $this->authorize('create', User::class);

        $createAdmin->execute($request->validated());

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Yönetici oluşturuldu.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.admins.edit', ['admin' => $user]);
    }

    public function update(
        UpdateAdminUserRequest $request,
        User $user,
        UpdateAdminUserAction $updateAdmin,
    ): RedirectResponse {
        $this->authorize('update', $user);

        $updateAdmin->execute($user, $request->validated());

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Yönetici güncellendi.');
    }

    public function destroy(User $user, DeleteAdminUserAction $deleteAdmin): RedirectResponse
    {
        $this->authorize('delete', $user);

        /** @var User $actor */
        $actor = auth()->user();

        $deleteAdmin->execute($actor, $user);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Yönetici silindi.');
    }
}
