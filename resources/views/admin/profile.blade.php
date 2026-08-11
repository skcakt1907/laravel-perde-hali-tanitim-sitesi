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
                <div class="sifre-satir">
                    <input type="password" name="password" id="yeniSifre" class="form-input"
                           autocomplete="new-password" minlength="10">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="sifreGoster()"
                            id="sifreGozBtn" title="Göster / gizle">
                        <i data-lucide="eye"></i>
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="sifreUret()"
                            title="Güçlü bir şifre üret">
                        <i data-lucide="dices"></i> Rastgele üret
                    </button>
                </div>
                <div class="form-help">
                    En az 10 karakter, harf ve rakam içermeli.
                    <strong>Rastgele üret</strong>'e basarsan 16 karakterlik bir şifre oluşturulur
                    ve iki alana da yazılır — kaydetmeden önce bir yere kopyala.
                </div>
            </div>
            <div class="form-group mb-0">
                <label class="form-label">Yeni şifre (tekrar)</label>
                <input type="password" name="password_confirmation" id="yeniSifreTekrar"
                       class="form-input" autocomplete="new-password" minlength="10">
            </div>
        </div>
    </div>

    <style>
        .sifre-satir { display: flex; gap: 8px; align-items: stretch; }
        .sifre-satir .form-input { flex: 1 1 auto; min-width: 0; }
        .sifre-satir .btn { flex: 0 0 auto; white-space: nowrap; }
        .sifre-uretilen {
            margin-top: 10px; padding: 10px 12px; border-radius: 8px;
            background: var(--brand-soft, #eff4ff); border: 1px dashed var(--brand, #2563eb);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 15px; letter-spacing: .5px; word-break: break-all;
        }
    </style>

    <script>
        /* Şifre üretimi TARAYICIDA yapılır — üretilen şifre sunucuya yalnızca
           formu kaydederken, kullanıcının kendi isteğiyle gider.

           `crypto.getRandomValues` kullanılıyor, `Math.random` DEĞİL: Math.random
           kriptografik olarak güvenli değildir ve şifre üretiminde kullanılmamalı.

           Karakter kümesinde okunuşu karışan harfler (I l 1 O 0) bilinçli olarak
           yok — şifre telefonla okunacak ya da elle yazılacak olabilir. */
        function sifreUret() {
            const harfler = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';
            const rakamlar = '23456789';
            const isaretler = '!?*-_+=';
            const kume = harfler + rakamlar + isaretler;

            const rastgeleKarakter = (kaynak) => {
                // Modulo yanlılığını önlemek için aralık dışına düşeni atıyoruz
                const sinir = 256 - (256 % kaynak.length);
                const bayt = new Uint8Array(1);

                do {
                    crypto.getRandomValues(bayt);
                } while (bayt[0] >= sinir);

                return kaynak[bayt[0] % kaynak.length];
            };

            // Kural gereği en az bir harf ve bir rakam garanti
            let karakterler = [
                rastgeleKarakter(harfler),
                rastgeleKarakter(rakamlar),
                rastgeleKarakter(isaretler),
            ];

            while (karakterler.length < 16) {
                karakterler.push(rastgeleKarakter(kume));
            }

            // Garanti edilen karakterler baştaki sabit yerlerde kalmasın diye karıştır
            for (let i = karakterler.length - 1; i > 0; i--) {
                const j = rastgeleKarakter(Array.from({ length: i + 1 }, (_, k) => k));
                [karakterler[i], karakterler[j]] = [karakterler[j], karakterler[i]];
            }

            const sifre = karakterler.join('');

            document.getElementById('yeniSifre').value = sifre;
            document.getElementById('yeniSifreTekrar').value = sifre;

            gosterildi = false;
            sifreGoster();
            sifreyiYaz(sifre);
        }

        /** Üretilen şifreyi kopyalanabilir biçimde ekranda gösterir */
        function sifreyiYaz(sifre) {
            let kutu = document.getElementById('sifreUretilen');

            if (! kutu) {
                kutu = document.createElement('div');
                kutu.id = 'sifreUretilen';
                kutu.className = 'sifre-uretilen';

                /* "Yeni şifre" alanının HEMEN ALTINA konuyor, kartın en dibine
                   değil: en dipteki kutu sabit duran Kaydet çubuğunun altında
                   kalıp okunmuyordu. */
                document.getElementById('yeniSifre').closest('.form-group').after(kutu);
            }

            kutu.textContent = 'Üretilen şifre: ' + sifre + '  (kopyala, sonra Kaydet\'e bas)';
        }

        let gosterildi = false;

        function sifreGoster() {
            gosterildi = ! gosterildi;
            const tur = gosterildi ? 'text' : 'password';

            document.getElementById('yeniSifre').type = tur;
            document.getElementById('yeniSifreTekrar').type = tur;

            const btn = document.getElementById('sifreGozBtn');
            btn.innerHTML = '<i data-lucide="' + (gosterildi ? 'eye-off' : 'eye') + '"></i>';

            if (window.lucide) {
                lucide.createIcons();
            }
        }
    </script>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
