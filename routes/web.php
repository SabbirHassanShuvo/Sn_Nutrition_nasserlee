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
Route::get('test-html', function() {
    return view('backend.layout.settings.system')->render();
});
Route::get('test-error-log', function() {
    $logPath = ini_get('error_log');
    $out = "<h2>PHP Error Log Path: " . htmlspecialchars($logPath) . "</h2>";
    if ($logPath && file_exists($logPath)) {
        $lines = file($logPath);
        $lastLines = array_slice($lines, -50);
        $out .= "<pre>" . htmlspecialchars(implode("", $lastLines)) . "</pre>";
    } else {
        $out .= "Log file not found or not readable.";
    }
    
    $laravelLog = storage_path('logs/laravel.log');
    $out .= "<h2>Laravel Log Path: " . htmlspecialchars($laravelLog) . "</h2>";
    if (file_exists($laravelLog)) {
        $lines = file($laravelLog);
        $lastLines = array_slice($lines, -50);
        $out .= "<pre>" . htmlspecialchars(implode("", $lastLines)) . "</pre>";
    } else {
        $out .= "Laravel log not found.";
    }
    return $out;
});


// test





require_once __DIR__ .'/auth.php';
