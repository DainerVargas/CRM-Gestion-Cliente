<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Auth::loginUsingId(1);
    
    $sessions = \App\Models\SalesSession::all();
    foreach ($sessions as $session) {
        echo "Testing viewDetails for session " . $session->id . "\n";
        
        $component = app(\Livewire\LivewireManager::class)->new(\App\Livewire\Admin\Sales\SettlementManager::class);
        $component->mount();
        $component->viewDetails($session->id);
        
        $view = $component->render();
        $publicProperties = [
            'view' => $component->view,
            'selectedSession' => $component->selectedSession,
            'activeSession' => $component->activeSession,
        ];
        $view->with($publicProperties);
        
        $html = $view->render();
        echo "Render successful for session " . $session->id . ". Output length: " . strlen($html) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (\Throwable $t) {
    echo "Throwable: " . $t->getMessage() . " at " . $t->getFile() . ":" . $t->getLine() . "\n";
}
