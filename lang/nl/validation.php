<?php

/**
 * Alleen de regels die dit project daadwerkelijk gebruikt; al het andere valt
 * terug op de Engelse standaardteksten van Laravel.
 */
return [
    'required' => 'Vul het veld :attribute in.',
    'email'    => 'Voer een geldig e-mailadres in.',
    'date'     => ':attribute moet een geldige datum zijn.',
    'numeric'  => ':attribute moet een getal zijn.',
    'accepted' => 'Bevestig :attribute.',
    'in'       => 'De gekozen :attribute is ongeldig.',
    'max'      => [
        'string'  => ':attribute mag niet langer zijn dan :max tekens.',
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'file'    => ':attribute mag niet groter zijn dan :max kilobytes.',
    ],
    'min' => [
        'string'  => ':attribute moet minimaal :min tekens bevatten.',
        'numeric' => ':attribute moet minimaal :min zijn.',
    ],
    'mimes' => ':attribute moet een bestand van het type :values zijn.',
    'image' => ':attribute moet een afbeelding zijn.',

    'attributes' => [
        'name'    => 'naam',
        'phone'   => 'telefoon',
        'email'   => 'e-mail',
        'subject' => 'onderwerp',
        'message' => 'bericht',
        'note'    => 'bericht',
        'zip'     => 'postcode',
        'city'    => 'plaats',
        'address' => 'adres',
        'date'    => 'gewenste datum',
        'time'    => 'tijd',
        'privacy' => 'de privacyverklaring',
    ],
];
