<?php

/**
 * Only the rules actually used in this project; anything else falls back to
 * Laravel's own English defaults.
 */
return [
    'required' => 'Please fill in the :attribute field.',
    'email'    => 'Please enter a valid e-mail address.',
    'date'     => ':attribute must be a valid date.',
    'numeric'  => ':attribute must be a number.',
    'accepted' => 'Please confirm :attribute.',
    'in'       => 'The selected :attribute is invalid.',
    'max'      => [
        'string'  => ':attribute may not be longer than :max characters.',
        'numeric' => ':attribute may not be greater than :max.',
        'file'    => ':attribute may not be larger than :max kilobytes.',
    ],
    'min' => [
        'string'  => ':attribute must be at least :min characters.',
        'numeric' => ':attribute must be at least :min.',
    ],
    'mimes' => ':attribute must be a file of type :values.',
    'image' => ':attribute must be an image.',

    'attributes' => [
        'name'    => 'name',
        'phone'   => 'phone',
        'email'   => 'e-mail',
        'subject' => 'subject',
        'message' => 'message',
        'note'    => 'message',
        'zip'     => 'postcode',
        'city'    => 'town / city',
        'address' => 'address',
        'date'    => 'preferred date',
        'time'    => 'time',
        'privacy' => 'the privacy policy',
    ],
];
