<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap(); // Bootstrap all service providers and DB connections

// Login a user
$user = \App\Models\User::first();
if ($user) {
    auth()->login($user);
}

$request = Illuminate\Http\Request::create('/admin/product', 'POST', [
    'name' => 'Test Product ' . time(),
    'price' => 99.99,
    'category_id' => 1,
    'quantity' => 10,
    'batches' => [
        [
            'batch_id' => 1,
            'quantity' => 10,
            'expiry_date' => '2026-12-31'
        ]
    ]
]);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() >= 400) {
    echo "Content: " . substr($response->getContent(), 0, 3000) . "\n";
} else {
    echo "Redirect: " . $response->headers->get('Location') . "\n";
}
