<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonatorController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:donators')->group(function () {
    Route::get('/donator', [DonatorController::class, 'loggedInfo'])->name('Api.Donator.LoggedInfo');
    Route::put('/donator', [DonatorController::class, 'update'])->name('Api.Donator.Update');
    Route::get('/donations', [DonationController::class, 'list'])->name('Api.Donations.List');
    Route::post('/donations', [DonationController::class, 'create'])->name('Api.Donation.Create');
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

/*
    News Routes
*/
Route::get('/news', [NewsController::class, 'list'])->name('Api.News.List');
Route::get('/news/{id}', [NewsController::class, 'info'])->name('Api.News.Info');


/*
    Items Routes
*/
Route::get('/items', [ItemController::class, 'list'])->name('Api.Items.List');

