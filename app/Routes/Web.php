<?php


use App\Controllers\Bucket\BucketController;
use App\Controllers\Home;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('', [Home::class, 'index']);
$routes->group('bucket', function(RouteCollection $router){
    $router->get('', [BucketController::class, 'index']);
    $router->get('add', [BucketController::class, 'form_ae']);
    $router->post('save', [BucketController::class, 'add_bucket']);
    $router->get('edit/(:alphanum)', [BucketController::class, 'form_ae']);
    $router->get('hapus/(:alphanum)', [BucketController::class, 'hapus_bucket']);
});