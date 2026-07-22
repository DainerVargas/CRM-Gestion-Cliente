<?php
$file = 'resources/views/livewire/admin/sales/settlement-manager.blade.php';
$content = file_get_contents($file);

// Replace number_format($variable, 2) with number_format($variable ?? 0, 2)
// Be careful with expressions like $s->amount - $s->paid_amount
// Let's just use floatval or ?? 0 carefully.

// It's safer to just replace any remaining closing_cash and starting_cash, and amount properties if they are null.
$content = preg_replace('/number_format\(\$([a-zA-Z0-9_\->]+),\s*2\)/', 'number_format($$$1 ?? 0, 2)', $content);

file_put_contents($file, $content);
echo "Replaced properly!\n";
