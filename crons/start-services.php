<?php

// Prejdi o úroveň vyššie do root projektu
$projectPath = dirname(__DIR__);

// Funkcia na kontrolu či proces beží
function isProcessRunning($processName) {
    exec("pgrep -f '$processName'", $output);
    return !empty($output);
}

// Spustenie Reverb
if (!isProcessRunning('artisan reverb:start')) {
    $command = "nohup php $projectPath/artisan reverb:start >> $projectPath/storage/logs/reverb.log 2>&1 &";
    exec($command);
    echo "Reverb started\n";
}

// Spustenie Queue Worker
if (!isProcessRunning('artisan queue:work')) {
    $command = "nohup php $projectPath/artisan queue:work --sleep=3 --tries=3 --max-time=3600 >> $projectPath/storage/logs/queue.log 2>&1 &";
    exec($command);
    echo "Queue worker started\n";
}

echo "Services check completed at " . date('Y-m-d H:i:s') . "\n";
