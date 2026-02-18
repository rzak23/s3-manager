<?php


use App\Controllers\Bucket\BucketController;
use App\Controllers\Home;
use App\Controllers\ObjectController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('', [Home::class, 'index']);
$routes->group('bucket', function(RouteCollection $router){
    $router->get('', [BucketController::class, 'index']);
    $router->post('save', [BucketController::class, 'add_bucket']);
    $router->get('hapus/(:alphanum)', [BucketController::class, 'hapus_bucket']);
    $router->get('open/(:any)', [BucketController::class, 'open_bucket']);
});

$routes->group('object', function(RouteCollection $router){
    $router->post('add/(:any)', [ObjectController::class, 'upload_file']);
    $router->get('hapus/(:any)/(:any)', [ObjectController::class, 'hapus_file']);
    $router->get('download/(:any)', [ObjectController::class, 'download_file']);
});