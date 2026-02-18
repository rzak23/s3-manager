<?php

namespace App\Utils;

class OptionUtils{
    public static function get_list_acl(): array
    {
        return [
            'private'               => 'Private',
            'public-read'           => 'Public Read',
            'public-read-write'     => 'Public Read Write',
            'authenticated-read'    => 'Authenticated Read'
        ];
    }
}