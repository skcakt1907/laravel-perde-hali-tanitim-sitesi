<?php

/** Ziyaretçiye giden onay e-postaları */
return [
    'aufmass' => [
        'subject'  => 'Ücretsiz ölçü talebiniz',
        'greeting' => 'Merhaba :name,',
        'intro'    => 'talebiniz için teşekkür ederiz. Talebiniz bize ulaştı; ölçü randevusunu '
            . 'kararlaştırmak için sizi telefonla arayacağız.',
        'summary'  => 'Bize gönderdikleriniz',
        'note'     => 'Ölçü, yol ve danışmanlık sizin için ücretsiz ve yükümlülük içermez.',
        'contact'  => 'Eklemek istediğiniz bir şey varsa bu e-postayı yanıtlayabilir ya da '
            . 'bizi arayabilirsiniz: :phone',
    ],

    'contact' => [
        'subject'  => 'Mesajınız bize ulaştı',
        'greeting' => 'Merhaba :name,',
        'intro'    => 'mesajınız için teşekkür ederiz. Mesajınız bize ulaştı; genellikle aynı '
            . 'iş günü içinde dönüş yapıyoruz.',
        'summary'  => 'Mesajınız',
        'contact'  => 'Acil bir durumsa bize telefonla ulaşabilirsiniz: :phone',
    ],

    'fields' => [
        'name'    => 'Ad Soyad',
        'phone'   => 'Telefon',
        'email'   => 'E-posta',
        'subject' => 'İlgilendiğiniz',
        'place'   => 'Yer',
        'address' => 'Adres',
        'date'    => 'Tercih ettiğiniz tarih',
        'note'    => 'Mesaj',
    ],

    'signature' => 'Saygılarımızla',
    'auto'      => 'Bu e-posta otomatik olarak gönderilmiştir.',
];
