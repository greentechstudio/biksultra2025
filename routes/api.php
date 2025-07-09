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

// WhatsApp validation API (no CSRF required)
Route::post('/validate-whatsapp', [\App\Http\Controllers\AuthController::class, 'validateWhatsAppAjax'])->name('api.validate.whatsapp');

// Registration API
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerApi'])->name('api.register');
// Route::post('/register-debug', [\App\Http\Controllers\AuthController::class, 'debugRegisterApi'])->name('api.register.debug');
// Route::post('/register-simple', [\App\Http\Controllers\AuthController::class, 'registerApiSimple'])->name('api.register.simple');

// Location autocomplete API
Route::get('/location/search', [\App\Http\Controllers\LocationController::class, 'searchRegencies'])->name('api.location.search');
Route::get('/location/regency/{id}', [\App\Http\Controllers\LocationController::class, 'getRegency'])->name('api.location.regency');
Route::get('/location/smart-search', [\App\Http\Controllers\LocationController::class, 'smartSearch'])->name('api.location.smart-search');

// Ticket availability check
Route::get('/check-ticket-availability', [\App\Http\Controllers\AuthController::class, 'checkTicketAvailability'])->name('api.check.ticket.availability');
Route::post('/check-ticket-availability', [\App\Http\Controllers\AuthController::class, 'checkTicketAvailability'])->name('api.check.ticket.availability.post');
