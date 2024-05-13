<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('https://botfactory.pabloherrerof.es');
});

require __DIR__.'/auth.php';
