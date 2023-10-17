<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistDiscogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getAll', [ArtistDiscogController::class, 'getAll']);
Route::get('/combined-sales', [ArtistDiscogController::class, 'albumsSoldPerArtist']);
Route::get('/albums', [ArtistDiscogController::class, 'AllArtistAlbums']);
Route::get('/albums/{artist}', [ArtistDiscogController::class, 'allAlbumsByArtist']);
Route::get('/topone', [ArtistDiscogController::class, 'getTopOne']);
Route::get('/topten', [ArtistDiscogController::class, 'getTopTen']);

Route::put('/albums/{id}', [ArtistDiscogController::class, 'updateArtist']);

Route::delete('/albums/{id}', [ArtistDiscogController::class, 'deleteArtist']);


