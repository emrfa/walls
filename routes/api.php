<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/loss-events', function (Request $request, \App\Services\LossEventService $service) {

    // Get filter data from the query string
    $dateFilter = $request->query('date', '2025-11-06');
    $machineFilter = $request->query('machine', 'RIA10');

    $data = $service->getLossEvents($dateFilter, $machineFilter);

    return response()->json([
        'data' => $data,
        'filters_used' => [
            'date' => $dateFilter,
            'machine' => $machineFilter
        ]
    ]);
});