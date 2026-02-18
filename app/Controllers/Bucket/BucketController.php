<?php

namespace App\Controllers\Bucket;

use App\Controllers\BaseController;
use App\ThirdParty\Nos;
use App\Utils\OptionUtils;
use CodeIgniter\HTTP\ResponseInterface;

class BucketController extends BaseController
{
    public function index(): string
    {
        helper('form');

        $s3 = Nos::connect();
        $list_bucket = [];

        $buckets = $s3->listBuckets();
        foreach($buckets['Buckets'] as $bucket){
            $list_bucket[] = [
                'nama' => $bucket['Name']
            ];
        }

        $data = [
            'data'      => $list_bucket,
            'list_acl'  => OptionUtils::get_list_acl()
        ];
        return view('pages/bucket/bucket_list', $data);
    }

    public function add_bucket(): \CodeIgniter\HTTP\RedirectResponse
    {
        try{
            $s3 = Nos::connect();

            $nama_bucket    = $this->request->getPost('nama-bucket');
            $acl            = $this->request->getPost('acl');

            $s3->createBucket([
                'Bucket'    => $nama_bucket,
                'ACL'       => $acl
            ]);
            return redirect()->back()
                ->with('success', "Bucket {$nama_bucket} berhasil dibuat");
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Tambah Bucket : {$e->getMessage()}");
        }
    }

    public function hapus_bucket(string $nama_bucket): \CodeIgniter\HTTP\RedirectResponse
    {
        try{
            $s3 = Nos::connect();

            $s3->deleteBucket([
                'Bucket' => $nama_bucket
            ]);
            return redirect()->back()
                ->with('success', 'Bucket berhasil dihapus');
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Hapus Bucket : {$e->getMessage()}");
        }
    }
}
