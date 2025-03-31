<?php

use App\Http\Controllers\AudioExerciseController;
use App\Http\Controllers\ConjugationExerciseController;
use App\Http\Controllers\GrammarExerciseController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\VerbController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::redirect('/', '/words')->name('home');

// Group routes with common prefix and name
Route::group(['prefix' => 'words', 'as' => 'words.'], function () {
    Route::get('/', [WordController::class, 'index'])->name('index');
    Route::post('/check', [WordController::class, 'check'])->name('check');
});

Route::group(['prefix' => 'sentences', 'as' => 'sentence.'], function () {
    Route::get('/', [SentenceController::class, 'index'])->name('index');
    Route::post('/check', [SentenceController::class, 'check'])->name('check');
});

Route::group(['prefix' => 'grammar', 'as' => 'grammar.'], function () {
    Route::get('/', [GrammarExerciseController::class, 'index'])->name('index');
    Route::post('/check', [GrammarExerciseController::class, 'check'])->name('check');
});

Route::group(['prefix' => 'conjugation', 'as' => 'conjugation.'], function () {
    Route::get('/', [ConjugationExerciseController::class, 'index'])->name('index');
    Route::post('/check', [ConjugationExerciseController::class, 'check'])->name('check');
});

Route::group(['prefix' => 'verbs', 'as' => 'verbs.'], function () {
    Route::get('/', [VerbController::class, 'index'])->name('index');
    Route::post('/check', [VerbController::class, 'check'])->name('check');
});

Route::group(['prefix' => 'audio', 'as' => 'audio.'], function () {
    Route::get('/', [AudioExerciseController::class, 'index'])->name('index');
    Route::post('/check', [AudioExerciseController::class, 'check'])->name('check');
});
