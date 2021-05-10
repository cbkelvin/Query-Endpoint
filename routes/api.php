<?php
use App\Http\Controllers\CafesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'v1'], function(){
    //... Other Routes
    Route::get('/cafes', [CafesController::class, 'search']);
});

