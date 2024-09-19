<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\PicReportController;
use App\Http\Controllers\CustomMenuController;
use App\Http\Controllers\KartuStockController;
use App\Http\Controllers\TextEditorController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\InboundReturController;
use App\Http\Controllers\RequestRefundController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\LaporanKaryawanController;
use App\Http\Controllers\ManualComplaintController;
use App\Http\Controllers\StockOpnameRequestController;
use App\Http\Controllers\KartuStockMenuDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::match(['get', 'post'], '/dashboard', function () {
    return view('dashboard');
});

Route::post('/getDataDashboard', [App\Http\Controllers\HomeController::class, 'getData'])->name('getData');

Route::resource('user', '\App\Http\Controllers\UserController');
Route::resource('expedisi', '\App\Http\Controllers\ExpedisiController');
Route::resource('serahterima', '\App\Http\Controllers\SerahTerimaController');

Route::get('/serah-terima/total-paket', [SerahTerimaController::class, 'getTotalPaket'])->name('serahterima.getTotalPaket');
Route::post('/scanBarcode', [App\Http\Controllers\SerahTerimaController::class, 'scanBarcode'])->name('scanBarcode');
Route::get('/listTemp', [App\Http\Controllers\SerahTerimaController::class, 'listTemp'])->name('listTemp');
Route::delete('/destroyTemp/{id}', [App\Http\Controllers\SerahTerimaController::class, 'destroyTemp'])->name('destroyTemp');
Route::delete('/deleteTempAll', [App\Http\Controllers\SerahTerimaController::class, 'deleteTempAll'])->name('deleteTempAll');
Route::delete('/destroyResi/{id}', [App\Http\Controllers\SerahTerimaController::class, 'destroyResi'])->name('destroyResi');

Route::get('/countItem', [App\Http\Controllers\SerahTerimaController::class, 'countItem'])->name('countItem');
Route::get('/printTandaTerima/{id}', [App\Http\Controllers\SerahTerimaController::class, 'printTandaTerima'])->name('printTandaTerima');
Route::get('/printTandaTerimaTerm/{id}', [App\Http\Controllers\SerahTerimaController::class, 'printTandaTerimaTerm'])->name('printTandaTerimaTerm');
Route::post('/getDataSerahTerima', [App\Http\Controllers\SerahTerimaController::class, 'getDataSerahTerima'])->name('getDataSerahTerima');
Route::get('/listFilterBulan', [App\Http\Controllers\SerahTerimaController::class, 'listFilterBulan'])->name('listFilterBulan');
Route::get('/cariResi', [App\Http\Controllers\SerahTerimaController::class, 'cariResi'])->name('cariResi');
Route::post('/updateCatatan', [App\Http\Controllers\SerahTerimaController::class, 'updateCatatan'])->name('updateCatatan');
Route::get('/getDetailSerahTerimaById/{id}', [App\Http\Controllers\SerahTerimaController::class, 'getDetailSerahTerimaById'])->name('getDetailSerahTerimaById');
Route::post('/updateDetail', [App\Http\Controllers\SerahTerimaController::class, 'updateDetail'])->name('updateDetail');

Route::get('/export', [App\Http\Controllers\SerahTerimaController::class, 'export'])->name('serahterima.exportExcel');
Route::get('/exportById/{id}', [App\Http\Controllers\SerahTerimaController::class, 'exportById'])->name('exportById');
Route::get('/exportByBulan', [App\Http\Controllers\SerahTerimaController::class, 'exportByBulan'])->name('exportByBulan');

Route::get('/blacklist', [App\Http\Controllers\BlacklistController::class, 'index'])->name('blacklist');
Route::get('/blacklist/create', [App\Http\Controllers\BlacklistController::class, 'create'])->name('blacklist.create');
Route::post('/blacklist/store', [App\Http\Controllers\BlacklistController::class, 'store'])->name('blacklist.store');
Route::get('/blacklist/{id}/show', [App\Http\Controllers\BlacklistController::class, 'show'])->name('blacklist.show');
//Route::delete('/blacklist/{id}/destroy', [App\Http\Controllers\BlacklistController::class, 'destroy'])->name('blacklist.destroy');
Route::delete('/blacklist/destroy/{id}', [App\Http\Controllers\BlacklistController::class, 'destroy'])->name('blacklist.destroy');

Route::post('/blacklist/getDataBlacklist', [App\Http\Controllers\BlacklistController::class, 'getDataBlacklist'])->name('blacklist.getDataBlacklist');
Route::post('/blacklist/scanBarcode', [App\Http\Controllers\BlacklistController::class, 'scanBarcode'])->name('blacklist.scanBarcode');
Route::delete('/blacklist/destroyResi/{id}', [App\Http\Controllers\BlacklistController::class, 'destroyResi'])->name('destroyResi');

Route::get('/log-activitas', [App\Http\Controllers\LogActivitasController::class, 'index'])->name('logactivitas');
Route::post('/log-activitas/detail', [App\Http\Controllers\LogActivitasController::class, 'detail'])->name('logactivitasdetail');

Route::get('/katalog', [App\Http\Controllers\KatalogController::class, 'index'])->name('katalog');
Route::post('/katalog/store', [App\Http\Controllers\KatalogController::class, 'store'])->name('katalog.store');
Route::get('/katalog/{id}/show', [App\Http\Controllers\KatalogController::class, 'show'])->name('katalog.show');
Route::post('katalog/store-child', [App\Http\Controllers\KatalogController::class, 'storeChild'])->name('katalog.storeChild');
Route::put('katalog/update/{id}', [App\Http\Controllers\KatalogController::class, 'update'])->name('katalog.update');
Route::put('katalog/update-photo/{id}', [App\Http\Controllers\KatalogController::class, 'updatePhoto'])->name('katalog.updatePhoto');
Route::put('katalog/update-photo/{id}', [App\Http\Controllers\KatalogController::class, 'updatePhoto'])->name('katalog.updatePhoto');
Route::put('katalog/update-folder/{id}', [App\Http\Controllers\KatalogController::class, 'updateChild'])->name('katalog.updateChild');
Route::put('katalog/update-folder-child/{id}', [App\Http\Controllers\KatalogController::class, 'updateGrandChild'])->name('katalog.updateGrandChild');

// dipakai
Route::post('katalog/store-photo', [App\Http\Controllers\KatalogController::class, 'storePhoto'])->name('katalog.storePhoto');
Route::post('katalog/store-detail-photo', [App\Http\Controllers\KatalogController::class, 'storeDetailPhoto'])->name('katalog.storeDetailPhoto');
Route::put('katalog/update-detail-photo', [App\Http\Controllers\KatalogController::class, 'updateDetailPhoto'])->name('katalog.updateDetailPhoto');
Route::get('/photos/{photoId}', [App\Http\Controllers\KatalogController::class, 'fetchPhotoData'])->name('photos.fetchPhotoData');
// Route::post('katalog/update-media', [App\Http\Controllers\KatalogController::class, 'updateMedia'])->name('projects.updateMedia');
Route::put('/katalog/updateDetailPhoto/{id}', [App\Http\Controllers\KatalogController::class, 'updateDetailPhoto'])->name('katalog.updateDetailPhoto');
Route::delete('/photos/{photoId}', [App\Http\Controllers\KatalogController::class, 'destroyPhoto'])->name('photos.destroyPhoto');
Route::post('/photos/{fileId}', [App\Http\Controllers\KatalogController::class, 'destroyPhotoDropzone'])->name('photos.destroyPhotoDropzone');
Route::delete('variations/{variationId}', [App\Http\Controllers\KatalogController::class, 'destroyVariation'])->name('variations.destroyVariation');
Route::get('/katalog/{parentId}/{childId}/detail', [App\Http\Controllers\KatalogController::class, 'detail'])->name('katalog.detail');
Route::get('/katalog/{childId}', [App\Http\Controllers\KatalogController::class, 'childDetail'])->name('katalog.childDetail');
Route::post('/update-parent', [App\Http\Controllers\KatalogController::class, 'update'])->name('update.parent');
Route::delete('/katalog/destroy/{id}', [App\Http\Controllers\KatalogController::class, 'destroy'])->name('katalog.destroy');
Route::delete('/katalog/destroy-child/{id}', [App\Http\Controllers\KatalogController::class, 'destroyChild'])->name('katalog.destroyChild');
Route::delete('/katalog/destroy-grand-child/{id}', [App\Http\Controllers\KatalogController::class, 'destroyGrandChild'])->name('katalog.destroyGrandChild');
Route::delete('/katalog/destroy-photo/{id}', [App\Http\Controllers\KatalogController::class, 'destroyPhoto'])->name('katalog.destroyPhoto');
Route::post('katalog/store-grand-child', [App\Http\Controllers\KatalogController::class, 'storeGrandChild'])->name('katalog.storeGrandChild');
Route::get('katalog/photo-detail/{photoId}', [App\Http\Controllers\KatalogController::class, 'photoDetail'])->name('katalog.photoDetail');
Route::post('katalog/temp-photo', [App\Http\Controllers\KatalogController::class, 'uploadTempPhoto'])->name('projects.uploadTempPhoto');

Route::post('katalog/media', [App\Http\Controllers\KatalogController::class,'storeMedia'])->name('projects.storeMedia');
Route::post('katalog/media/delete', [App\Http\Controllers\KatalogController::class,'deleteMedia'])->name('projects.deleteMedia');

Route::post('katalog/create-menu', [App\Http\Controllers\KatalogController::class, 'createMenu'])->name('katalog.createMenu');
Route::post('katalog/create-folder', [App\Http\Controllers\KatalogController::class, 'createFolder'])->name('katalog.createFolder');





// Route::get('/setting-akses', [App\Http\Controllers\SettingAksesController::class, 'index'])->name('settingakses');
// Route::post('/setting-akses/update-akses', [App\Http\Controllers\SettingAksesController::class, 'update'])->name('updateakses');

// Route::group(['prefix' => 'retur', 'as' => 'retur.', 'controller' => ReturController::class], function () {
//     Route::post('/dataTable', 'dataTable')->name('dataTable');
// });
Route::resource('retur', ReturController::class);
Route::post('retur/dataTable', [ReturController::class, 'dataTable'])->name('retur.dataTable');
Route::get('retur/export/excel', [ReturController::class, 'export'])->name('retur.exportExcel');
Route::resource('manual-complaint', ManualComplaintController::class);
Route::post('manual-complaint/dataTable', [ManualComplaintController::class, 'dataTable'])->name('manual-complaint.dataTable');
Route::get('manual-complaint/export/excel', [ManualComplaintController::class, 'export'])->name('manual-complaint.exportExcel');
Route::resource('stock-opname-request', StockOpnameRequestController::class);
Route::post('stock-opname-request/dataTable', [StockOpnameRequestController::class, 'dataTable'])->name('stock-opname-request.dataTable');
Route::get('stock-opname-request/export/excel', [StockOpnameRequestController::class, 'export'])->name('stock-opname-request.exportExcel');
Route::resource('request-refund', RequestRefundController::class);
Route::get('request-refund/export/excel', [RequestRefundController::class, 'export'])->name('request-refund.exportExcel');
Route::post('request-refund/dataTable', [RequestRefundController::class, 'dataTable'])->name('request-refund.dataTable');
Route::resource('shop', ShopController::class);
Route::resource('pic-report', PicReportController::class);
Route::resource('role-permission', RolePermissionController::class);
Route::resource('inbound-retur', InboundReturController::class);
Route::post('/inbound-retur/getData', [InboundReturController::class, 'getData'])->name('inbound-retur.getData');
Route::post('/inbound-retur/scanBarcode', [InboundReturController::class, 'scanBarcode'])->name('inbound-retur.scanBarcode');

Route::group(['prefix' => 'custom-menu', 'as' => 'custom-menu.'],function () {
    Route::get('/', [CustomMenuController::class, 'index'])->name('index');
    Route::get('/create', [CustomMenuController::class, 'create'])->name('create');
    Route::post('/', [CustomMenuController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CustomMenuController::class, 'edit'])->name('edit');
    Route::get('/menu/{slug}', [CustomMenuController::class, 'menu'])->name('menu');
    Route::get('/menu/{slug}/create', [CustomMenuController::class, 'menuCreate'])->name('menu.create');
    Route::get('/menu/{slug}/edit/{id}', [CustomMenuController::class, 'menuEdit'])->name('menu.edit');
    Route::post('/menu/{slug}', [CustomMenuController::class, 'menuStore'])->name('menu.store');
    Route::post('/menu/{slug}/dataTable', [CustomMenuController::class, 'dataTableMenu'])->name('menu.dataTable');
    Route::post('/dataTable', [CustomMenuController::class, 'dataTable'])->name('dataTable');
    Route::delete('/{id}', [CustomMenuController::class, 'destroy'])->name('destroy');
    Route::delete('/{slug}/{id}', [CustomMenuController::class, 'menuDestroy'])->name('menu.destroy');
    Route::get('/{slug}/export-excel', [CustomMenuController::class, 'menuExportExcel'])->name('menu.exportExcel');
    Route::post('/menu/{slug}/{id}/update-editable', [CustomMenuController::class, 'menuUpdateEditable'])->name('menu.updateEditable');
    Route::post('/menu/{id}/duplicate-menu', [CustomMenuController::class, 'duplicateMenu'])->name('menu.duplicate-menu');
    Route::get('/menu/{slug}/{id}/', [CustomMenuController::class, 'showMenu'])->name('menu.show-menu');
});

Route::resource('laporan-karyawan', LaporanKaryawanController::class);
Route::post('laporan-karyawan/dataTable', [LaporanKaryawanController::class, 'dataTable'])->name('laporan-karyawan.dataTable');
Route::get('laporan-karyawan/export/excel', [LaporanKaryawanController::class, 'export'])->name('laporan-karyawan.exportExcel');

Route::group(['prefix' => 'textEditor'], function() {
    Route::post('/uploadPhoto',  [TextEditorController::class, 'uploadPhoto'])->name('uploadPhoto');
    Route::post('/deletePhoto',  [TextEditorController::class, 'deletePhoto'])->name('deletePhoto');
});

// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




//reset pass
Route::patch('/reset/{id}', [App\Http\Controllers\UserController::class, 'reset'])->name('reset');