<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\QueueController;

Route::get('/', function () {
    return view('queue');
});

// Using web routes as pseudo api since it's a simple app without api install
Route::get('/api/stands', [QueueController::class, 'getStands']);
Route::get('/api/queues', [QueueController::class, 'getQueues']);
Route::post('/api/queues', [QueueController::class, 'storeQueue']);
