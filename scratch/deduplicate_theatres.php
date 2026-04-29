<?php

use App\Models\Theatre;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$theatres = Theatre::all()->groupBy('name');
$deletedCount = 0;

foreach ($theatres as $name => $group) {
    if ($group->count() > 1) {
        $duplicates = $group->slice(1);
        foreach ($duplicates as $duplicate) {
            $duplicate->delete();
            $deletedCount++;
        }
    }
}

echo "Deduplication complete. Deleted $deletedCount duplicate theatres.";
