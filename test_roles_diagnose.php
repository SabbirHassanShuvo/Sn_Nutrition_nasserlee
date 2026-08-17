<?php

use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "=== ROLES IN SPATIE TABLE ===\n";
foreach (Role::all() as $role) {
    echo "- ID: {$role->id}, Name: {$role->name}, Guard: {$role->guard_name}\n";
}

echo "\n=== PERMISSIONS IN SPATIE TABLE ===\n";
foreach (Permission::all() as $permission) {
    echo "- ID: {$permission->id}, Name: {$permission->name}, Guard: {$permission->guard_name}\n";
}

echo "\n=== USERS IN DATABASE ===\n";
foreach (User::all() as $user) {
    echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, DB Role Col: {$user->role}, Spatie Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
}
