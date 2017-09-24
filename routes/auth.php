<?php

Route::get('/login', 'LoginController@index')->name('login');
Route::get('/register', 'RegisterController@index')->name('register');
Route::get('/contract', 'RegisterController@contract')->name('contract');
