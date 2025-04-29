<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//group of the students routes
Route::prefix('v1/students')->group(function () {
    Route::get('/', [StudentController::class, 'index']); // Get all students
    Route::get('/{id}', [StudentController::class, 'show']); // Get a single student by ID
    Route::post('/', [StudentController::class, 'store']); // Create a student
    Route::put('/{id}', [StudentController::class, 'update']); // Update a student by ID
    Route::delete('/{id}', [StudentController::class, 'destroy']); // Delete a student by ID

    // Extra functionalities
    Route::get('/paginate', [StudentController::class, 'paginate']); // Paginate students
    Route::get('/search', [StudentController::class, 'search']); // Search students dynamically
    Route::get('/sort', [StudentController::class, 'sort']); // Sort students
    Route::get('/filter', [StudentController::class, 'filter']); // Filter students by age

    // Search specific student attributes
    Route::get('/searchById', [StudentController::class, 'searchById']);
    Route::get('/searchByName', [StudentController::class, 'searchByName']);
    Route::get('/searchByEmail', [StudentController::class, 'searchByEmail']);
    Route::get('/searchByPhone', [StudentController::class, 'searchByPhone']);
    Route::get('/searchByAddress', [StudentController::class, 'searchByAddress']);
    Route::get('/searchByAge', [StudentController::class, 'searchByAge']);
});

