<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $session = \App\Models\SalesSession::create([
        'date' => date('Y-m-d'),
        'start_time' => date('H:i:s'),
        'starting_cash' => 200,
        'status' => 'open',
        'user_id' => 1
    ]);
    foreach (['k10', 's45', 's10'] as $type) {
        \App\Models\CylinderInventory::create([
            'sales_session_id' => $session->id,
            'cylinder_type' => $type,
            'initial_full' => 0,
            'initial_empty' => 0
        ]);
    }
    echo "Success: Created session " . $session->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
