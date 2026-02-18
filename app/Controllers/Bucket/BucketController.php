<?php

namespace App\Controllers\Bucket;

use App\Controllers\BaseController;
use App\ThirdParty\Nos;
use App\Utils\OptionUtils;
use Carbon\Carbon;
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

    public function open_bucket(string $nama_bucket): string|\CodeIgniter\HTTP\RedirectResponse
    {
        helper(['number', 'form']);

        try{
            $s3 = Nos::connect();

            $object_data = $s3->listObjectsV2([
                'Bucket' => $nama_bucket
            ]);

            $list_object = [];
            if(isset($object_data['Contents'])){
                foreach($object_data['Contents'] as $content){
                    $list_object[] = [
                        'filename'          => $content['Key'],
                        'last_date'         => Carbon::parse($content['LastModified'])->toDateTimeString(),
                        'size'              => number_to_size($content['Size']),
                        'edit_action'       => "object/edit/{$nama_bucket}/{$content['Key']}",
                        'hapus_action'      => "object/hapus/{$nama_bucket}/{$content['Key']}",
                        'download_action'   => "object/download/{$nama_bucket}/{$content['Key']}"
                    ];
                }
            }

            $data = [
                'data'          => $list_object,
                'add_action'    => "object/add/{$nama_bucket}",
            ];
            return view('pages/object/object_list', $data);
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Buka Bucket : {$e->getMessage()}");
        }
    }
}
