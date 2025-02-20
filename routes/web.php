<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Livewire\Contact;
use App\Livewire\Contact\EditContact;
use App\Livewire\ContactShow;
use App\Livewire\ViewContact;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
