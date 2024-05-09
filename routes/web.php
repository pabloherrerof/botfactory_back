<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('http://localhost:3000');
});

require __DIR__.'/auth.php';
