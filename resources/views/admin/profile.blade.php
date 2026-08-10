@extends('admin.layout')
@section('title', 'Profil')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Profil</h1>
        <div class="page-subtitle">Giriş bilgileriniz ve şifreniz</div>
    </div>
</div>

<form action="{{ route('admin.profile.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="grid-2">
        <div class="card">
            <div class="section-title"><i data-lucide="user"></i> Hesap bilgileri</div>

            <div class="form-group">
                <label class="form-label">Ad Soyad <span class="required">*</span></label>
                <input name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">E-posta <span class="required">*</span></label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                <div class="form-help">Panele bu adresle giriş yapıyorsunuz.</div>
            </div>
            <div class="form-group mb-0">
                <label class="form-label">Telefon</label>
                <input name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
            </div>
        </div>

        <div class="card">
            <div class="section-title"><i data-lucide="lock"></i> Şifre değiştir</div>
            <div class="alert alert-info" style="margin-bottom:14px">
                <i data-lucide="info"></i>
                <div>Şifrenizi değiştirmek istemiyorsanız bu üç alanı boş bırakın.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Mevcut şifre</label>
                <input type="password" name="current_password" class="form-input" autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label">Yeni şifre</label>
                <input type="password" name="password" class="form-input" autocomplete="new-password">
            </div>
            <div class="form-group mb-0">
                <label class="form-label">Yeni şifre (tekrar)</label>
                <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password">
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
