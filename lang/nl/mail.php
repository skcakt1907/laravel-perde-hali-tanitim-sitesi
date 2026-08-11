<?php

/** Bevestigingsmails aan de bezoeker */
return [
    'aufmass' => [
        'subject'  => 'Uw aanvraag voor een gratis inmeetafspraak',
        'greeting' => 'Hallo :name,',
        'intro'    => 'bedankt voor uw aanvraag. Wij hebben deze ontvangen en bellen u om een tijd '
            . 'voor de inmeetafspraak af te spreken.',
        'summary'  => 'Wat u ons heeft doorgegeven',
        'note'     => 'Inmeten, voorrijden en advies zijn gratis en zonder verplichtingen.',
        'contact'  => 'Wilt u nog iets toevoegen? Antwoord dan gewoon op deze e-mail of bel ons: :phone',
    ],

    'contact' => [
        'subject'  => 'Wij hebben uw bericht ontvangen',
        'greeting' => 'Hallo :name,',
        'intro'    => 'bedankt voor uw bericht. Wij hebben het ontvangen en antwoorden normaal '
            . 'gesproken dezelfde werkdag.',
        'summary'  => 'Uw bericht',
        'contact'  => 'Is het dringend? Dan kunt u ons telefonisch bereiken op :phone',
    ],

    'fields' => [
        'name'    => 'Naam',
        'phone'   => 'Telefoon',
        'email'   => 'E-mail',
        'subject' => 'Interesse in',
        'place'   => 'Locatie',
        'address' => 'Adres',
        'date'    => 'Gewenste datum',
        'note'    => 'Bericht',
    ],

    'signature' => 'Met vriendelijke groet',
    'auto'      => 'Deze e-mail is automatisch verzonden.',
];
