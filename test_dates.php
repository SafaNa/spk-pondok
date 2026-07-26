<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Current now(): " . \Carbon\Carbon::now()->toDateTimeString() . "\n";
echo "Current now()->startOfDay(): " . \Carbon\Carbon::now()->startOfDay()->toDateTimeString() . "\n\n";

$leaves = \App\Models\Licensing\MassLeave::all();
echo "Total MassLeave count: " . $leaves->count() . "\n";

foreach ($leaves as $leaf) {
    $start = \Carbon\Carbon::parse($leaf->start_date);
    $subDay = \Carbon\Carbon::parse($leaf->start_date)->subDay()->startOfDay();
    $gte = \Carbon\Carbon::now()->startOfDay()->gte($subDay);
    $lt = \Carbon\Carbon::now()->startOfDay()->lt($subDay);
    echo "ID: {$leaf->id} | Title: {$leaf->title} | Start: {$start->toDateString()} | subDay: {$subDay->toDateString()} | GTE (Controller block): " . ($gte ? 'TRUE (BLOCKED)' : 'FALSE (ALLOWED)') . " | LT (Blade show): " . ($lt ? 'TRUE (SHOWN)' : 'FALSE (HIDDEN)') . "\n";
}
