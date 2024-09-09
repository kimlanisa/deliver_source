<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ExpedisiController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\SerahTerimaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/ping', function () {
        return 'pong';
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', [AuthController::class, 'login']);
        $router->post('/logout', [AuthController::class, 'logout']);
        $router->post('/refresh-token', [AuthController::class, 'refreshToken']);
    });

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/profile', [AuthController::class, 'profile']);
        $router->get('/config-app', function () {
            $date_now = date('Y-m-d');
            return [
                'date_now' => $date_now,
            ];
        });
        $router->group(['prefix' => 'serah-terima'], function () use ($router) {
            $router->get('/', [SerahTerimaController::class, 'getDataSerahTerima']);
            $router->post('/scan-barcode', [SerahTerimaController::class, 'scanBarcode']);
            $router->post('/', [SerahTerimaController::class, 'store']);
            $router->get('/{id}', [SerahTerimaController::class, 'show']);
            $router->post('/{id}/update', [SerahTerimaController::class, 'update']);
            $router->delete('/{id}', [SerahTerimaController::class, 'destroy']);
            $router->delete('/destroyResi/{id}', [SerahTerimaController::class, 'destroyResi'])->name('destroyResi');
            $router->get('/getDetailSerahTerimaById/{id}', [SerahTerimaController::class, 'getDetailSerahTerimaById']);
        });

        $router->get('expedisi', [ExpedisiController::class, 'list']);

        $router->group(['prefix' => 'blacklist'], function () use ($router) {
            $router->get('/', [BlacklistController::class, 'getDataBlacklist']);
            $router->post('/scan-barcode', [BlacklistController::class, 'scanBarcode']);
            $router->post('/', [BlacklistController::class, 'store']);
            $router->get('/{id}', [BlacklistController::class, 'show']);
            $router->delete('/{id}', [BlacklistController::class, 'destroy']);
            $router->post('/{id}/update', [BlacklistController::class, 'update']);
        });

        $router->get('/katalog/{childId}', [KatalogController::class, 'childDetail']);
        $router->get('/parent/{parentId}', [KatalogController::class, 'parentDetail']);
    });

});