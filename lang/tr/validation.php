<?php

return [
    'required' => ':attribute alanı zorunludur.',
    'email'    => 'Geçerli bir e-posta adresi girin.',
    'date'     => ':attribute geçerli bir tarih olmalıdır.',
    'numeric'  => ':attribute sayı olmalıdır.',
    'accepted' => ':attribute onaylanmalıdır.',
    'in'       => ':attribute seçimi geçersiz.',
    'max'      => [
        'string'  => ':attribute en fazla :max karakter olabilir.',
        'numeric' => ':attribute en fazla :max olabilir.',
        'file'    => ':attribute en fazla :max kilobayt olabilir.',
    ],
    'min' => [
        'string'  => ':attribute en az :min karakter olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
    ],
    'mimes' => ':attribute :values türünde bir dosya olmalıdır.',
    'image' => ':attribute bir görsel olmalıdır.',

    'attributes' => [
        'name'    => 'Ad Soyad',
        'phone'   => 'Telefon',
        'email'   => 'E-posta',
        'subject' => 'Konu',
        'message' => 'Mesaj',
        'note'    => 'Mesaj',
        'zip'     => 'Posta kodu',
        'city'    => 'Şehir',
        'address' => 'Adres',
        'date'    => 'Tarih',
        'time'    => 'Saat',
        'privacy' => 'Gizlilik politikası',
    ],
];
