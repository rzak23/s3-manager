<?php

namespace App\ThirdParty;

use Aws\S3\S3Client;

class Nos{
    public static function connect(): S3Client
    {
        $url = 'https://'.env('S3_ENDPOINT');
        return new S3Client([
            'version' => 'latest',
            'region' => 'idn',
            'endpoint' => $url,
            'credentials' => [
                'key' => env('S3_KEY'),
                'secret' => env('S3_SECRET')
            ]
        ]);
    }
}