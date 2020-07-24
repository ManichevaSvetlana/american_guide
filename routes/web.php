<?php

use Illuminate\Support\Facades\Route;

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
// Landing page
Route::get('/', function () {
    return view('app.landing');
});
// Redirect to products page
Route::get('/home', function () {
    return redirect('/my-products');
})->name('home');

/* Support Routes */
// Show the instructions page
Route::get('/instructions', 'UserController@showInstructionsPage');
// Show the terms page
Route::get('/terms', 'UserController@showTermsPage');
// Show the support page
Route::get('/support', 'UserController@showSupportPage');
// Send support request
Route::post('/support', 'UserController@sendSupportMessage')->name('support');
/* End of Support Routes */

/* Payment Routes */
// Send payment details and redirect to liqpay
Route::post('/payment/{product}', 'Payment\PaymentController@generateForm');
/* End of Support Routes */

/* App Routes */
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['verified']], function () {
        // Get my products page
        Route::get('/my-products', 'UserController@showProductsPage');
        // Get the product page
        Route::get('/my-products/{product}', 'UserController@showProductPage');
        // Get the lecture page
        Route::get('/my-products/{product}/{lecture}', 'UserController@showProductLecturePage');
        // Get frame if is_frame lecture
        Route::get('/frame/{lecture}', 'LectureController@showFrame');
        // Ajax routes
        Route::group(['middleware' => ['ajax']], function () {
            // Send lecture action: process
            Route::get('/lecture-action/{lecture}/{action}', 'LectureUserInteractionController@saveAction');
        });
    });
    // Get profile page
    Route::get('/profile', 'UserController@showProfilePage');
    // Post profile data update
    Route::post('/profile', 'UserController@updateProfile')->name('profile');

});
/* End of App Routes */

Auth::routes(['verify' => true]);


