<?php
require __DIR__.'/bootstrap/app.php';
$app = app();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = [
    'students', 'guardians', 'student_guardian', 'rayons', 'rooms', 'education_levels', 
    'users', 'settings', 'departments', 'academic_years', 'violation_categories', 
    'violation_types', 'violation_records', 'student_licenses', 'leave_reasons', 
    'leave_categories', 'license_extensions', 'mass_leaves', 'mass_leave_students', 
    'spp_payments', 'memorization_types', 'student_memorizations', 
    'student_memorization_items', 'sub_criteria'
];

$schema = [];
foreach($tables as $t) {
    try {
        $cols = DB::select("SHOW COLUMNS FROM $t");
        $schema[$t] = $cols;
    } catch (\Exception $e) {}
}

file_put_contents('schema_dump.json', json_encode($schema, JSON_PRETTY_PRINT));
echo "Done";
