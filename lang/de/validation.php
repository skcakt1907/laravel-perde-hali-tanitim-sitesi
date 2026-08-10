<?php

/**
 * Nur die Regeln, die in diesem Projekt tatsächlich verwendet werden.
 * Alles Übrige fällt auf Laravels englische Standardmeldungen zurück.
 */
return [
    'required' => 'Bitte füllen Sie das Feld :attribute aus.',
    'email'    => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
    'date'     => ':attribute muss ein gültiges Datum sein.',
    'numeric'  => ':attribute muss eine Zahl sein.',
    'accepted' => 'Bitte bestätigen Sie :attribute.',
    'in'       => 'Die Auswahl bei :attribute ist ungültig.',
    'max'      => [
        'string'  => ':attribute darf höchstens :max Zeichen lang sein.',
        'numeric' => ':attribute darf höchstens :max sein.',
        'file'    => ':attribute darf höchstens :max Kilobyte groß sein.',
    ],
    'min' => [
        'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
        'numeric' => ':attribute muss mindestens :min sein.',
    ],
    'mimes' => ':attribute muss eine Datei vom Typ :values sein.',
    'image' => ':attribute muss ein Bild sein.',

    'attributes' => [
        'name'    => 'Name',
        'phone'   => 'Telefon',
        'email'   => 'E-Mail',
        'subject' => 'Betreff',
        'message' => 'Nachricht',
        'note'    => 'Nachricht',
        'zip'     => 'Postleitzahl',
        'city'    => 'Ort',
        'address' => 'Adresse',
        'date'    => 'Wunschtermin',
        'time'    => 'Uhrzeit',
        'privacy' => 'Datenschutzerklärung',
    ],
];
