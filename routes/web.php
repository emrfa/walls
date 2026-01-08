<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::redirect('/', '/hmi-command/timeline');



// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// API Routes (Mocking a real API)
Route::prefix('api')->group(function () {
    Route::get('/commands', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->getCommands(
            $request->input('page', 1),
            $request->input('per_page', 10),
            $request->input('filter', []),
            $request->input('sort_field', 'code'),
            $request->input('sort_direction', 'asc')
        );
    });

    Route::post('/commands', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->storeCommand($request->all());
    });

    Route::get('/commands/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->getCommand($id);
    });

    Route::put('/commands/{id}', function ($id, \Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->updateCommand($id, $request->all());
    });

    Route::delete('/commands/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->deleteCommand($id);
    });

    Route::get('/categories', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->getCategories(
            $request->input('page', 1),
            $request->input('per_page', 10),
            $request->input('filter', []),
            $request->input('sort_field', 'code'),
            $request->input('sort_direction', 'asc')
        );
    });

    Route::get('/users', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->getUsers(
            $request->input('page', 1),
            $request->input('per_page', 10),
            $request->input('filter', []),
            $request->input('sort_field', 'nip'),
            $request->input('sort_direction', 'asc')
        );
    });

    Route::post('/users', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->storeUser($request->all());
    });

    Route::get('/users/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->getUser($id);
    });

    Route::put('/users/{id}', function ($id, \Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->updateUser($id, $request->all());
    });

    Route::delete('/users/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->deleteUser($id);
    });

    Route::get('/timeline', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->getTimeline(
            $request->input('page', 1),
            $request->input('per_page', 10),
            $request->input('filter', []),
            $request->input('sort_field', 'set_time'),
            $request->input('sort_direction', 'desc')
        );
    });

    Route::get('/device-modules', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->getDeviceModules(
            $request->input('page', 1),
            $request->input('per_page', 10),
            $request->input('filter', []),
            $request->input('sort_field', 'tag_code'),
            $request->input('sort_direction', 'asc')
        );
    });

    Route::post('/device-modules', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->storeDeviceModule($request->all());
    });

    Route::get('/device-modules/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->getDeviceModule($id);
    });

    Route::put('/device-modules/{id}', function ($id, \Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
        return $apiService->updateDeviceModule($id, $request->all());
    });

    Route::delete('/device-modules/{id}', function ($id, \App\Services\ApiService $apiService) {
        return $apiService->deleteDeviceModule($id);
    });
});

// View Routes
Route::get('/hmi-command/timeline', function () {
    return view('hmi-command.timeline');
})->name('hmi-command.timeline');

Route::get('/device-module', function () {
    return view('device-module.index');
})->name('device-module.index');

Route::get('/device-module/create', function () {
    return view('device-module.create');
})->name('device-module.create');

Route::get('/device-module/{id}/edit', function ($id) {
    return view('device-module.edit', ['id' => $id]);
})->name('device-module.edit');

Route::get('/command-master', function () {
    return view('command-master.index');
})->name('command-master.index');

Route::get('/command-master/create', function () {
    return view('command-master.create');
})->name('command-master.create');

Route::get('/command-master/{id}/edit', function ($id) {
    return view('command-master.edit', ['id' => $id]);
})->name('command-master.edit');

Route::get('/command-category', function () {
    return view('command-category.index');
})->name('command-category.index');

Route::get('/command-category/create', function () {
    return view('command-category.create');
})->name('command-category.create');

Route::get('/command-category/{id}/edit', function ($id) {
    return view('command-category.edit', ['id' => $id]);
})->name('command-category.edit');

// API Routes
Route::post('/api/categories', function (\Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
    return $apiService->storeCategory($request->all());
});

Route::get('/api/categories/{id}', function ($id, \App\Services\ApiService $apiService) {
    return $apiService->getCategory($id);
});

Route::put('/api/categories/{id}', function ($id, \Illuminate\Http\Request $request, \App\Services\ApiService $apiService) {
    return $apiService->updateCategory($id, $request->all());
});

Route::delete('/api/categories/{id}', function ($id, \App\Services\ApiService $apiService) {
    return $apiService->deleteCategory($id);
});

Route::get('/users', function () {
    return view('users.index');
})->name('users.index');

Route::get('/users/create', function () {
    return view('users.create');
})->name('users.create');

Route::get('/users/{id}/edit', function ($id) {
    return view('users.edit', ['id' => $id]);
})->name('users.edit');

Route::get('/profile', function (\App\Services\ApiService $apiService) {
    return view('profile.index', ['user' => $apiService->getCurrentUser()]);
})->name('profile');

// require __DIR__.'/auth.php';
