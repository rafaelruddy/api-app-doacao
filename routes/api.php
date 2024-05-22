<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonatorController;
use App\Http\Controllers\InstitutionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:donators')->group(function () {
    Route::get('/donator', [DonatorController::class, 'loggedInfo'])->name('Api.Donator.LoggedInfo');
});

Route::post('/login', [DonatorController::class, 'login'])->name('Api.Donator.Login');
Route::post('/donators', [DonatorController::class, 'register'])->name('Api.Donator.Register');


/*
    Institution Routes
*/
Route::get('/institutions', [InstitutionController::class, 'list'])->name('Api.Institutions.List');
Route::get('/institution/{id}', [InstitutionController::class, 'info'])->name('Api.Institutions.Info');


/*
    Campaign Routes
*/
Route::get('/campaigns', [CampaignController::class, 'list'])->name('Api.Campaigns.List');
Route::get('/campaign/{id}', [CampaignController::class, 'info'])->name('Api.Campaigns.Info');
