<?php

/** Ziyaretçiye giden onay e-postaları */
return [
    'aufmass' => [
        'subject'  => 'Ihre Anfrage für das kostenlose Aufmaß',
        'greeting' => 'Guten Tag :name,',
        'intro'    => 'vielen Dank für Ihre Anfrage. Wir haben sie erhalten und melden uns '
            . 'telefonisch bei Ihnen, um einen Termin für das Aufmaß abzustimmen.',
        'summary'  => 'Das haben Sie uns geschickt',
        'note'     => 'Aufmaß, Anfahrt und Beratung sind für Sie kostenlos und unverbindlich.',
        'contact'  => 'Wenn Sie etwas ergänzen möchten, antworten Sie einfach auf diese E-Mail '
            . 'oder rufen Sie uns an: :phone',
    ],

    'contact' => [
        'subject'  => 'Ihre Nachricht ist bei uns eingegangen',
        'greeting' => 'Guten Tag :name,',
        'intro'    => 'vielen Dank für Ihre Nachricht. Wir haben sie erhalten und antworten '
            . 'in der Regel am selben Werktag.',
        'summary'  => 'Ihre Nachricht',
        'contact'  => 'Falls es dringend ist, erreichen Sie uns telefonisch unter :phone',
    ],

    'fields' => [
        'name'    => 'Name',
        'phone'   => 'Telefon',
        'email'   => 'E-Mail',
        'subject' => 'Interesse',
        'place'   => 'Ort',
        'address' => 'Adresse',
        'date'    => 'Wunschtermin',
        'note'    => 'Nachricht',
    ],

    'signature' => 'Mit freundlichen Grüßen',
    'auto'      => 'Diese E-Mail wurde automatisch verschickt.',
];
