<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'admin@example.com')->first();
echo "Name: " . $user->name . "\n";
echo "Role ID: " . $user->role_id . "\n";
echo "Role relation name: " . ($user->role ? $user->role->name : 'NULL') . "\n";
