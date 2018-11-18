<?php

Route::get('/', function () {
    return \App\User::all();
});
