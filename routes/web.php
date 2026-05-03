<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
Route::get('/{page?}', function ($page = null) {
    return redirect()->route('backend.dashboard.index');
})->where('page', 'home|index');

Route::get('middleware', function() {
    $collection = collect(Route::getRoutes())->map(function($r){
        if(isset($r->action['middleware']))
            return $r->action['middleware'];
    })->flatten();
    return array_unique($collection->toArray());
});

Route::get('session-key', function(){
    return Session::get('session_key') ?? "No session key";
});

Route::get('session-forget', function(){
    return session()->flush();
});
require_once __DIR__ .'/auth.php';
