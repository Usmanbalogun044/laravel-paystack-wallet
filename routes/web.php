<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user=Auth::user();
    $wallet = $user->wallet;

    if (!$wallet) {
        // Optionally create a wallet if it doesn't exist
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0.00, // Default balance
        ]);
    }
    return view('dashboard', compact('wallet'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/walletfundng',[PaymentController::class, 'fundwallet'])->name('fundwallet');
    Route::post('/fund-wallet', [PaymentController::class, 'Wallet'])->name('wallet');
    Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');
});

require __DIR__.'/auth.php';
