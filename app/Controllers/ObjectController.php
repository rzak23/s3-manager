<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\ThirdParty\Nos;
use CodeIgniter\HTTP\ResponseInterface;

class ObjectController extends BaseController
{
    public function upload_file(string $nama_bucket): \CodeIgniter\HTTP\RedirectResponse
    {
        try{
            $s3 = Nos::connect();

            $file_src = $this->request->getFile('file-up');

            $file_name = $file_src->getClientName();
            $s3->putObject([
                'Bucket' => $nama_bucket,
                'Key' => $file_name,
                'SourceFile' => $file_src->getTempName()
            ]);

            return redirect()->back()
                ->with('success', "File {$file_name} berhasil diupload");
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Upload : {$e->getMessage()}");
        }
    }

    public function hapus_file(string $nama_bucket, string $file_name): \CodeIgniter\HTTP\RedirectResponse
    {
        try{
            $s3 = Nos::connect();

            $parameters = array_merge(['Bucket' => $nama_bucket, 'Key' => $file_name], []);
            $s3->deleteObject($parameters);

            return redirect()->back()
                ->with('success', "File {$file_name} berhasil dihapus dari Bucket {$nama_bucket}");
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Error Hapus File : {$e->getMessage()}");
        }
    }

    public function download_file(string $nama_bucket): \CodeIgniter\HTTP\DownloadResponse|\CodeIgniter\HTTP\RedirectResponse|null
    {
        try{
            $s3 = Nos::connect();

            $uri            = service('uri');
            $segments       = $uri->getSegments();
            $file_segments  = array_slice($segments, 2);
            $file_path      = implode('/', $file_segments);
            $file_path      = str_replace("{$nama_bucket}/", '', $file_path);
            $file_path      = urldecode($file_path);

            $result = $s3->getObject([
                'Bucket' => $nama_bucket,
                'Key' => $file_path
            ]);

            $file_content = (string) $result['Body'];
            $file_name = basename($file_path);
            return $this->response->download($file_name, $file_content);
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Download : {$e->getMessage()}");

        }
    }
}
