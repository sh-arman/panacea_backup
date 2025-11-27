<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Eloquent ORM with database...\n\n";

try {
    // Test with Company model
    echo "1. Testing Company model:\n";
    $companyCount = \Panacea\Company::count();
    echo "   Total companies: " . $companyCount . "\n";
    
    if ($companyCount > 0) {
        $company = \Panacea\Company::first();
        echo "   First company: " . ($company->name ?? 'N/A') . "\n";
    }
    echo "\n";
    
    // Test with User model if exists
    echo "2. Testing Medicine model:\n";
    $medicineCount = \Panacea\Medicine::count();
    echo "   Total medicines: " . $medicineCount . "\n";
    
    if ($medicineCount > 0) {
        $medicine = \Panacea\Medicine::first();
        echo "   First medicine: " . ($medicine->name ?? $medicine->id ?? 'N/A') . "\n";
    }
    echo "\n";
    
    // Test with Order model
    echo "3. Testing Order model:\n";
    $orderCount = \Panacea\Order::count();
    echo "   Total orders: " . $orderCount . "\n";
    echo "\n";
    
    // Test with Code model
    echo "4. Testing Code model:\n";
    $codeCount = \Panacea\Code::count();
    echo "   Total codes: " . $codeCount . "\n";
    
    if ($codeCount > 0) {
        $code = \Panacea\Code::first();
        echo "   First code: " . ($code->code ?? 'N/A') . "\n";
    }
    echo "\n";
    
    echo "✓ All Eloquent tests passed! Database is working properly.\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "\nClass: " . get_class($e) . "\n";
    echo "\nFile: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
