<?php
$file = 'resources/views/livewire/admin/sales/settlement-manager.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/number_format\(\$([a-zA-Z0-9_\->]+)\s*-\s*\$([a-zA-Z0-9_\->]+),\s*2\)/', 'number_format(($$1 ?? 0) - ($$2 ?? 0), 2)', $content);

file_put_contents($file, $content);
echo "Replaced math expressions!\n";
