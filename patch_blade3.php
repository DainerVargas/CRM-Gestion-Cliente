<?php
$file = 'resources/views/livewire/admin/sales/settlement-manager.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/\$([a-zA-Z0-9_\->]+)->created_at->format\(/', 'optional($$1->created_at)->format(', $content);
$content = preg_replace('/\$([a-zA-Z0-9_\->]+)->updated_at->format\(/', 'optional($$1->updated_at)->format(', $content);

file_put_contents($file, $content);
echo "Replaced date formats!\n";
