<?php

use App\Http\Controllers\ReservationController;

Route::apiResource('reservations', ReservationController::class);
Route::apiResource('payments', PaymentController::class);
