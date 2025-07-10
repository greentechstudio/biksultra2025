<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\WhatsAppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\WhatsAppQueueController;
use App\Http\Controllers\UnpaidRegistrationsController;

Route::get('/', function () {
    return view('welcome');
});

// Backup route for old version
Route::get('/welcome-simple', function () {
    return view('welcome-simple');
});

// Test route
Route::get('/test', function () {
    return '<h1>Test berhasil!</h1><p>Laravel berjalan dengan baik.</p>';
});

// Simple test route
Route::get('/test-simple', function () {
    return view('test-simple');
});

// Debug route for WhatsApp API
Route::get('/debug/whatsapp/{number?}', function($number = '628114000805') {
    try {
        $controller = new \App\Http\Controllers\Api\WhatsAppController();
        $request = new \Illuminate\Http\Request();
        $request->merge(['number' => $number]);
        
        $response = $controller->checkNumber($request);
        
        return response()->json([
            'debug' => true,
            'test_number' => $number,
            'controller_response' => $response->getData()
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'debug' => true,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

// Authentication Routes  
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// WhatsApp Validation Route
Route::post('/validate-whatsapp', [AuthController::class, 'validateWhatsAppAjax'])->name('validate-whatsapp');

// Registration with Random Password
Route::get('/register-random-password', [AuthController::class, 'showRegisterRandomPassword'])->name('register.random-password')->middleware('guest');
Route::post('/register-random-password', [AuthController::class, 'registerWithRandomPassword'])->name('register.random-password.post')->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset/check', [PasswordResetController::class, 'checkUsername'])->name('password.reset.check');
Route::post('/password/reset', [PasswordResetController::class, 'sendResetLink'])->name('password.reset.send');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/password/reset/update', [PasswordResetController::class, 'resetPassword'])->name('password.reset.update');

// Test route for debugging username check
Route::get('/api/test-username/{number}', function($number) {
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
});

// Timezone info route
Route::get('/api/timezone-info', function() {
    return response()->json(server_time_info());
});

// WhatsApp verification routes
Route::post('/api/verify-whatsapp', [AuthController::class, 'verifyWhatsapp']);
Route::post('/api/confirm-payment', [AuthController::class, 'confirmPayment']);
Route::post('/api/check-whatsapp-number', [WhatsAppController::class, 'checkNumber']);

// Registration and Ticket Type API Routes
Route::get('/api/ticket-info', [\App\Http\Controllers\RegistrationController::class, 'getTicketInfo']);
Route::get('/api/registration-stats', [\App\Http\Controllers\RegistrationController::class, 'getStats']);

// Xendit webhook
Route::post('/webhook/xendit', [\App\Http\Controllers\RegistrationController::class, 'xenditWebhook']);

// API routes for checking status
Route::get('/api/check-verification/{userId}', function($userId) {
    $user = \App\Models\User::find($userId);
    return response()->json(['verified' => $user ? $user->whatsapp_verified : false]);
});

Route::get('/api/check-payment/{userId}', function($userId) {
    $user = \App\Models\User::find($userId);
    return response()->json(['confirmed' => $user ? $user->payment_confirmed : false]);
});

// API Routes for Xendit webhook
Route::prefix('api')->group(function () {
    Route::post('/xendit/webhook', [App\Http\Controllers\Api\XenditWebhookController::class, 'handleWebhook'])
        ->name('xendit.webhook');
    
    // Payment status management
    Route::get('/payment/status', [App\Http\Controllers\Api\PaymentStatusController::class, 'checkStatus'])
        ->name('payment.status.check');
    Route::post('/payment/status', [App\Http\Controllers\Api\PaymentStatusController::class, 'updateStatus'])
        ->name('payment.status.update');
    Route::get('/payment/pending', [App\Http\Controllers\Api\PaymentStatusController::class, 'getPendingPayments'])
        ->name('payment.pending');
});

// Payment success/failure pages
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/failed', function () {
    return view('payment.failed');
})->name('payment.failed');

// Dashboard Routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    // Routes available for all authenticated users
    // Redirect all users to appropriate dashboard
    Route::get('/dashboard', function() {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('profile.show'); // Regular users go to profile
    })->name('dashboard');
    
    // Profile Routes - Available for all users
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Admin Routes - Admin Only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/recent-registrations', [AdminDashboardController::class, 'recentRegistrations'])->name('recent-registrations');
        Route::get('/recent-registrations/export', [AdminDashboardController::class, 'exportRecentRegistrations'])->name('recent-registrations.export');
        Route::get('/whatsapp-verification', [AdminDashboardController::class, 'whatsappVerification'])->name('whatsapp-verification');
        Route::get('/payment-confirmation', [AdminDashboardController::class, 'paymentConfirmation'])->name('payment-confirmation');
        Route::get('/profile-management', [AdminDashboardController::class, 'profile'])->name('profile-management');
        Route::post('/profile-management', [AdminDashboardController::class, 'updateProfile'])->name('profile-management.update');
        
        // Settings Management
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        
        // Jersey Sizes
        Route::post('/settings/jersey-sizes', [SettingsController::class, 'storeJerseySize'])->name('settings.jersey-sizes.store');
        Route::put('/settings/jersey-sizes/{jerseySize}', [SettingsController::class, 'updateJerseySize'])->name('settings.jersey-sizes.update');
        Route::delete('/settings/jersey-sizes/{jerseySize}', [SettingsController::class, 'deleteJerseySize'])->name('settings.jersey-sizes.delete');
        
        // Race Categories
        Route::post('/settings/race-categories', [SettingsController::class, 'storeRaceCategory'])->name('settings.race-categories.store');
        Route::put('/settings/race-categories/{raceCategory}', [SettingsController::class, 'updateRaceCategory'])->name('settings.race-categories.update');
        Route::delete('/settings/race-categories/{raceCategory}', [SettingsController::class, 'deleteRaceCategory'])->name('settings.race-categories.delete');
        
        // Blood Types
        Route::post('/settings/blood-types', [SettingsController::class, 'storeBloodType'])->name('settings.blood-types.store');
        Route::put('/settings/blood-types/{bloodType}', [SettingsController::class, 'updateBloodType'])->name('settings.blood-types.update');
        Route::delete('/settings/blood-types/{bloodType}', [SettingsController::class, 'deleteBloodType'])->name('settings.blood-types.delete');
        
        // Event Sources
        Route::post('/settings/event-sources', [SettingsController::class, 'storeEventSource'])->name('settings.event-sources.store');
        Route::put('/settings/event-sources/{eventSource}', [SettingsController::class, 'updateEventSource'])->name('settings.event-sources.update');
        Route::delete('/settings/event-sources/{eventSource}', [SettingsController::class, 'deleteEventSource'])->name('settings.event-sources.delete');
        
        // WhatsApp Queue Management
        Route::get('/whatsapp-queue', [WhatsAppQueueController::class, 'index'])->name('whatsapp-queue.index');
        Route::get('/whatsapp-queue/status', [WhatsAppQueueController::class, 'status'])->name('whatsapp-queue.status');
        Route::post('/whatsapp-queue/clear', [WhatsAppQueueController::class, 'clear'])->name('whatsapp-queue.clear');
        Route::post('/whatsapp-queue/force-process', [WhatsAppQueueController::class, 'forceProcess'])->name('whatsapp-queue.force-process');
        Route::post('/whatsapp-queue/send-test', [WhatsAppQueueController::class, 'sendTest'])->name('whatsapp-queue.send-test');
        
        // Unpaid Registrations Management
        Route::get('/unpaid-registrations', function() {
            return view('admin.unpaid-registrations');
        })->name('unpaid-registrations.index');
        Route::get('/whatsapp-queue/unpaid-status', [WhatsAppQueueController::class, 'unpaidStatus'])->name('whatsapp-queue.unpaid-status');
        Route::post('/whatsapp-queue/force-cleanup', [WhatsAppQueueController::class, 'forceCleanup'])->name('whatsapp-queue.force-cleanup');
        Route::post('/whatsapp-queue/force-reminders', [WhatsAppQueueController::class, 'forceReminders'])->name('whatsapp-queue.force-reminders');
        
        // Ticket Types Management
        Route::get('/ticket-types', [\App\Http\Controllers\Admin\TicketTypeController::class, 'index'])->name('ticket-types.index');
        Route::get('/ticket-types/create', [\App\Http\Controllers\Admin\TicketTypeController::class, 'create'])->name('ticket-types.create');
        Route::post('/ticket-types', [\App\Http\Controllers\Admin\TicketTypeController::class, 'store'])->name('ticket-types.store');
        Route::get('/ticket-types/{ticketType}/edit', [\App\Http\Controllers\Admin\TicketTypeController::class, 'edit'])->name('ticket-types.edit');
        Route::put('/ticket-types/{ticketType}', [\App\Http\Controllers\Admin\TicketTypeController::class, 'update'])->name('ticket-types.update');
        Route::patch('/ticket-types/{ticketType}/toggle-active', [\App\Http\Controllers\Admin\TicketTypeController::class, 'toggleActive'])->name('ticket-types.toggle-active');
        Route::get('/ticket-types/stats', [\App\Http\Controllers\Admin\TicketTypeController::class, 'getStats'])->name('ticket-types.stats');
    });
});

// Unpaid Registrations API routes
Route::prefix('api/unpaid-registrations')->group(function () {
    Route::get('stats', [UnpaidRegistrationsController::class, 'stats']);
    Route::post('dry-run', [UnpaidRegistrationsController::class, 'dryRun']);
    Route::post('send-reminders', [UnpaidRegistrationsController::class, 'sendReminders']);
    Route::post('cleanup', [UnpaidRegistrationsController::class, 'cleanup']);
    Route::post('process-all', [UnpaidRegistrationsController::class, 'processAll']);
    Route::post('create-test-user', [UnpaidRegistrationsController::class, 'createTestUser']);
    Route::post('create-test-batch', [UnpaidRegistrationsController::class, 'createTestBatch']);
    Route::get('users', [UnpaidRegistrationsController::class, 'getUsers']);
    Route::get('logs', [UnpaidRegistrationsController::class, 'getLogs']);
    Route::get('jobs', [UnpaidRegistrationsController::class, 'getJobs']);
});

// Test routes
Route::get('/test-debug', function () {
    return view('test-debug');
});
