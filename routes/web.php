<?php

use App\Mail\CronMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CronjobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCompareController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'localization'], function () {

    Route::get('/', [ProductsController::class,"index"])->name('user.view.home');

    Route::get('/detail/{id}/{siteId?}/{orderBy?}', [ProductsController::class,"detailProduct"])->name('user.view.detail');

    Route::get('/cate/{catagory}', [ProductsController::class,"getProductsByCategory"])->name('user.view.getProductsByCategory');

    Route::post('/search', [ProductsController::class,"search"])->name('user.view.search');
    Route::post('/searchProd', [ProductsController::class,"searchProd"])->name('user.view.searchProd');


    //Compare Page
    Route::get('/product/compare',[ProductCompareController::class,'view'])->name('user.product.compare');
    Route::post('/product/compare/add/{id}', [ProductCompareController::class,"addProductCompare"])->name('user.product.compare.add');
    Route::post('/product/compare/delete/{id}', [ProductCompareController::class,"deleteProductCompare"])->name('user.product.compare.delete');

    //Filter Page
    Route::get('/product/filter/{category?}/{brand?}',[ProductsController::class,'index_filter'])->name('user.product.filter');
    //Change Language
    Route::get('/change-language/{language}', [ProductsController::class,"changLanguage"])->name('user.language');
});





// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class,'show'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //User
    Route::get('/admin/list',[UserController::class,'index'])->name('admin.view.list');
    Route::post('/admin/list/create',[UserController::class,'store'])->name('admin.register');
    Route::delete('/admin/list/delete/{id}',[UserController::class,'deleteById'])->name('admin.delete');
    Route::put('/admin/list/updateRole/{id}',[UserController::class,'updateRoleById'])->name('admin.updateRole');
    //Product
    Route::get('/admin/product',[ProductsController::class,'indexAdmin'])->name('admin.view.product');
    Route::delete('/admin/product/delete/{id}',[ProductsController::class,'deleteProduct'])->name('admin.product.delete');
    Route::get('/admin/product/category/{id}/{orderBy?}', [ProductsController::class,"indexAdmin"])->name('admin.view.getProductsByCategory');
    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Cronjob Setting
    Route::get('/admin/cronjob',[CronjobController::class,'index'])->name('admin.view.cronjob');
    Route::post('/admin/runCronjob',[CronjobController::class,'run'])->name('admin.runCronjob');
    Route::post('/admin/setupTimeRunCron',[CronjobController::class,'setTime'])->name('admin.setTimeRun');

    // List sites
    Route::get('/admin/site/{id}',[SiteController::class,'index'])->name('admin.view.site');
    Route::delete('/admin/productSite/delete/{id}',[SiteController::class,'deleteProductSite'])->name('admin.productSite.delete');

});

Route::get('/testroute', function() {
    $name = "Funny Coder";

    // The email sending is done using the to method on the Mail facade
    Mail::to('testreceiver@gmail.com’')->send(new CronMail($name));
})->name('mail.cronjob');

require __DIR__.'/auth.php';
