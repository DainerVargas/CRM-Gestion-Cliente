<?php
$log = file_get_contents('storage/logs/laravel.log');
preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] local.ERROR:.*?\{main\}/s', $log, $matches);
if (!empty($matches[0])) {
    $last = end($matches[0]);
    $lines = explode("\n", $last);
    echo implode("\n", array_slice($lines, 0, 10));
} else {
    echo "No exceptions found.";
}
