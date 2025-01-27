<?php

use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\TempImageController;
//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');

Route::group(['prefix'=>'admin'], function (){

    Route::group(['middleware'=>'guest'],function (){

        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware'=>'admin.auth'],function (){

        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');

        //Category Route
        Route::get('/categories',[categoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[categoryController::class,'create'])->name('categories.create');
        Route::post('/categories',[categoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[categoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[categoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[categoryController::class,'destroy'])->name('categories.delete');

        //Sub-Category Routes
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');

        //Brand Routes
        Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}}',[BrandController::class,'update'])->name('brands.update');
        Route::delete('/brands/{brand}',[BrandController::class,'destroy'])->name('brands.delete');

        //Product Route
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/products',[ProductController::class,'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');


        //ProductSubCategories Route
        Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');


        //temp-images.create
        Route::post('/upload-temp-image',[TempImageController::class,'create'])->name('temp-images.create');

        Route::get('/getSlug',function (Request $request) {
            $slug = '';
            if(!empty($request->title)) {
                $slug =Str::slug($request->title);
            }

            return response()->json([
                'status'=> true,
                'slug' => $slug
            ]);
        })->name('getSlug');

    });


});
