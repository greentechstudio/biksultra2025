<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Api\WhatsAppController;

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

// Username Check API (No CSRF required)
Route::post('/check-username', [PasswordResetController::class, 'checkUsername'])->name('api.password.check.username');
Route::any('/check-username-simple', [PasswordResetController::class, 'checkUsernameSimple'])->name('api.password.check.username.simple');

// Test route for debugging username check
Route::get('/test-username/{number}', function($number) {
    $user = \App\Models\User::where('whatsapp_number', $number)->first();
    return response()->json([
        'number' => $number,
        'found' => !!$user,
        'user' => $user ? [
            'email' => $user->email,
            'name' => $user->name,
            'whatsapp_number' => $user->whatsapp_number
        ] : null
    ]);
})->name('api.test.username');

// WhatsApp API
Route::post('/whatsapp/check-number', [WhatsAppController::class, 'checkNumber'])->name('api.whatsapp.check');
Route::post('/whatsapp/send-message', [WhatsAppController::class, 'sendMessage'])->name('api.whatsapp.send');
