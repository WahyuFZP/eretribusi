<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/midtrans/notification', [MidtransWebhookController::class, 'notification']);



// Optional: health-check to verify path reachability
Route::get('/midtrans/notification/ping', function () {
	return response()->json(['ok' => true]);
});
