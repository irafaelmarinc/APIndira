<?php

return [
    'SIGN_IN_STAFF' => [
        'ci'        => 'required|string|exists:staff,ci',
        'password'  => 'required|string',
    ],
];