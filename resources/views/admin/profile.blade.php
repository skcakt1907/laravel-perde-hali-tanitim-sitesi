@extends('admin.layout')
@section('title', 'Profil')

@section('content')
<form action="{{ route('admin.profile.update') }}" method="POST" class="form-a">
    @csrf
    @method('PATCH')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-a">
                <h3 style="font-size:1rem;margin-top:0">Hesap Bilgileri</h3>
                <label>Ad Soyad</label>
                <input name="name" value="{{ old('name', $user->name) }}" required>
                <label>E-Posta <small style="color:var(--amut)">(giriş için)</small></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                <label>Telefon</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-a">
                <h3 style="font-size:1rem;margin-top:0">Şifre Değiştir</h3>
                <p style="font-size:.85rem;color:var(--amut);margin-top:0">Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakın.</p>
                <label>Mevcut Şifre</label>
                <input type="password" name="current_password" autocomplete="current-password">
                <label>Yeni Şifre</label>
                <input type="password" name="password" autocomplete="new-password">
                <label>Yeni Şifre (Tekrar)</label>
                <input type="password" name="password_confirmation" autocomplete="new-password">
            </div>
        </div>
    </div>

    <div class="mt-4"><button type="submit" class="btn-a"><i class="bi bi-check-lg"></i> Kaydet</button></div>
</form>
@endsection
