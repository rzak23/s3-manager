<?php

namespace App\Controllers\Object;

use App\Controllers\BaseController;
use App\ThirdParty\Nos;

class ObjectController extends BaseController
{
    public function upload_file(string $nama_bucket): \CodeIgniter\HTTP\ResponseInterface
    {
        try{
            $s3 = Nos::connect();

            $file_src   = $this->request->getFile('file-up');
            $acl        = $this->request->getPost('acl-file');
            $folder     = $this->request->getPost('folder');

            $file_name = $file_src->getClientName();
            $file_path = ($folder == "") ? $file_name : "{$folder}/{$file_name}";
            $s3->putObject([
                'Bucket'        => $nama_bucket,
                'Key'           => $file_path,
                'SourceFile'    => $file_src->getTempName(),
                'ACL'           => $acl
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => "File {$file_name} berhasil diupload"
            ])->setStatusCode(200);
        }catch(\Exception $e){
            return $this->response->setJSON([
                'status' => 'error',
                'message' => "Gagal upload file : {$e->getMessage()}"
            ])->setStatusCode(500);
        }
    }

    public function hapus_file(string $nama_bucket): \CodeIgniter\HTTP\RedirectResponse
    {
        try{
            $s3 = Nos::connect();

            $file_path = $this->get_file_path_from_uri($nama_bucket);
            $parameters = array_merge(['Bucket' => $nama_bucket, 'Key' => $file_path], []);
            $s3->deleteObject($parameters);

            return redirect()->back()
                ->with('success', "File {$file_path} berhasil dihapus dari Bucket {$nama_bucket}");
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Error Hapus File : {$e->getMessage()}");
        }
    }

    public function download_file(string $nama_bucket): \CodeIgniter\HTTP\DownloadResponse|\CodeIgniter\HTTP\RedirectResponse|null
    {
        try{
            $s3 = Nos::connect();

            $file_path = $this->get_file_path_from_uri($nama_bucket);
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

    public function info_file(string $nama_bucket){
        try{
            $s3 = Nos::connect();

            $file_path = $this->get_file_path_from_uri($nama_bucket);
            $result = $s3->getObjectAcl([
                'Bucket'    => $nama_bucket,
                'Key'       => $file_path
            ]);
            dd($result->get('Grants'));
        }catch(\Exception $e){
            return redirect()->back()
                ->with('error', "Gagal Info : {$e->getMessage()}");
        }
    }

    private function get_file_path_from_uri(string $nama_bucket): string
    {
        $uri            = service('uri');
        $segments       = $uri->getSegments();
        $file_segments  = array_slice($segments, 2);
        $file_path      = implode('/', $file_segments);
        $file_path      = str_replace("{$nama_bucket}/", '', $file_path);
        return urldecode($file_path);
    }
}
