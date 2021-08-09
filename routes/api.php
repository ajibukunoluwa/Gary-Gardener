<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ToDoItemController;

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

// No auth routes
Route::post('/login', [AuthController::class, 'login']);

// Auth routes
Route::group(['prefix' => 'to-do',  'middleware' => ['auth:sanctum']], function() {
    Route::get('/', [ToDoItemController::class, 'list']);
    Route::post('/', [ToDoItemController::class, 'store']);

    Route::prefix('{toDoItem}')->group(function () {
        Route::get('/', [ToDoItemController::class, 'show']);
        Route::post('/', [ToDoItemController::class, 'update']);
        Route::delete('/', [ToDoItemController::class, 'delete']);
        Route::post('/mark-as-complete', [ToDoItemController::class, 'markAsComplete']);

        // Reminders
        Route::post('/reminder', [ReminderController::class, 'store']);
    });
});
