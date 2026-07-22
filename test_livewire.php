<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Livewire\Livewire;

try {
    Auth::loginUsingId(1);
    
    $latestSession = \App\Models\SalesSession::latest()->first();
    echo "Testing viewDetails for session " . $latestSession->id . "\n";
    
    Livewire::test(\App\Livewire\Admin\Sales\SettlementManager::class)
        ->call('viewDetails', $latestSession->id);
        
    echo "Livewire test passed!\n";
} catch (\Throwable $e) {
    echo "Livewire test failed!\n";
    echo get_class($e) . "\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
