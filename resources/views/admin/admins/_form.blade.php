<div>
    <label for="name" class="mb-1.5 block text-sm font-medium">Ad</label>
    <input id="name" name="name" type="text" value="{{ old('name', $admin->name ?? '') }}" required class="form-input">
</div>
<div>
    <label for="email" class="mb-1.5 block text-sm font-medium">E-posta</label>
    <input id="email" name="email" type="email" value="{{ old('email', $admin->email ?? '') }}" required class="form-input">
</div>
<div>
    <label for="password" class="mb-1.5 block text-sm font-medium">Şifre</label>
    <input id="password" name="password" type="password" @required(! isset($admin)) class="form-input" autocomplete="new-password">
    @isset($admin)
        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Boş bırakırsanız şifre değişmez.</p>
    @endisset
</div>
<div>
    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium">Şifre tekrarı</label>
    <input id="password_confirmation" name="password_confirmation" type="password" @required(! isset($admin)) class="form-input" autocomplete="new-password">
</div>
