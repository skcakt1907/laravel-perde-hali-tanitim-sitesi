<?php

/** Confirmation e-mails sent to the visitor */
return [
    'aufmass' => [
        'subject'  => 'Your request for a free measuring visit',
        'greeting' => 'Hello :name,',
        'intro'    => 'thank you for your request. We have received it and will call you to '
            . 'arrange a time for the measuring visit.',
        'summary'  => 'What you sent us',
        'note'     => 'Measuring, travel and advice are free of charge and without obligation.',
        'contact'  => 'If you would like to add anything, simply reply to this e-mail or give '
            . 'us a call: :phone',
    ],

    'contact' => [
        'subject'  => 'We have received your message',
        'greeting' => 'Hello :name,',
        'intro'    => 'thank you for your message. We have received it and normally reply the '
            . 'same working day.',
        'summary'  => 'Your message',
        'contact'  => 'If it is urgent, you can reach us by phone on :phone',
    ],

    'fields' => [
        'name'    => 'Name',
        'phone'   => 'Phone',
        'email'   => 'E-mail',
        'subject' => 'Interested in',
        'place'   => 'Location',
        'address' => 'Address',
        'date'    => 'Preferred date',
        'note'    => 'Message',
    ],

    'signature' => 'Kind regards',
    'auto'      => 'This e-mail was sent automatically.',
];
