<?php

namespace App\Controllers;

use App\ThirdParty\Nos;

class Home extends BaseController
{
    public function index(): string
    {
        $s3 = Nos::connect();

        $buckets = $s3->listBuckets();
        $data_bucket = $buckets['Buckets'];

        $total_bucket = count($data_bucket);
        $data = [
            'menu'         => 'home',
            'total_bucket' => $total_bucket
        ];
        return view('pages/index', $data);
    }
}
