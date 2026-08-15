<div>
    <label for="name" class="mb-1.5 block text-sm font-medium">Ad</label>
    <input id="name" name="name" type="text" value="{{ old('name', $client->name ?? '') }}" required class="form-input">
</div>
<div>
    <label for="email" class="mb-1.5 block text-sm font-medium">E-posta</label>
    <input id="email" name="email" type="email" value="{{ old('email', $client->email ?? '') }}" class="form-input">
</div>
<div>
    <label for="company_name" class="mb-1.5 block text-sm font-medium">Firma</label>
    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $client->company_name ?? '') }}" class="form-input">
</div>
