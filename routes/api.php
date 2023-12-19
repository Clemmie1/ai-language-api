<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LanguageTranslationController;
use App\Http\Controllers\BatchDetectDominantLanguageController;
use App\Http\Controllers\BatchDetectLanguageEntitiesController;
use App\Http\Controllers\BatchDetectLanguageKeyPhrasesController;
use App\Http\Controllers\BatchDetectLanguagePiiEntitiesController;
use App\Http\Controllers\BatchDetectLanguageSentimentsController;
use App\Http\Controllers\BatchDetectLanguageTextClassificationController;

Route::get('/v1/ping', function () {
    info('Health check completed');

    return response()->json(['status' => 'ok']);
});


Route::post('/v1/Translation', [LanguageTranslationController::class, 'LanguageTranslation']);
Route::post('/v1/DominantLanguage', [BatchDetectDominantLanguageController::class, 'DetectDominantLanguage']);
Route::post('/v1/Entities', [BatchDetectLanguageEntitiesController::class, 'DetectLanguageEntities']);
Route::post('/v1/KeyPhrases', [BatchDetectLanguageKeyPhrasesController::class, 'DetectLanguageKeyPhrases']);
Route::post('/v1/PiiEntities', [BatchDetectLanguagePiiEntitiesController::class, 'DetectLanguagePiiEntities']);
Route::post('/v1/Sentiments', [BatchDetectLanguageSentimentsController::class, 'DetectLanguageSentiments']);
Route::post('/v1/TextClassification', [BatchDetectLanguageTextClassificationController::class, 'DetectLanguageTextClassification']);