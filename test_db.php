<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing database connection...\n\n";

try {
    // Test 1: Check if PDO extension is loaded
    echo "1. Checking PDO MySQL driver: ";
    if (extension_loaded('pdo_mysql')) {
        echo "✓ Loaded\n";
    } else {
        echo "✗ NOT LOADED - This is the problem!\n";
        exit(1);
    }
    
    // Test 2: Get database config
    echo "2. Database configuration:\n";
    $config = config('database.connections.mysql');
    echo "   Host: " . $config['host'] . "\n";
    echo "   Port: " . ($config['port'] ?? 'default') . "\n";
    echo "   Database: " . $config['database'] . "\n";
    echo "   Username: " . $config['username'] . "\n";
    echo "   Charset: " . $config['charset'] . "\n";
    echo "   Collation: " . $config['collation'] . "\n";
    echo "   Strict mode: " . ($config['strict'] ? 'enabled' : 'disabled') . "\n\n";
    
    // Test 3: Try to connect
    echo "3. Attempting database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "   ✓ Connection successful!\n\n";
    
    // Test 4: Query database
    echo "4. Testing query execution:\n";
    $result = DB::select('SELECT DATABASE() as db, VERSION() as version');
    echo "   Connected to database: " . $result[0]->db . "\n";
    echo "   MySQL version: " . $result[0]->version . "\n\n";
    
    // Test 5: List tables
    echo "5. Listing tables:\n";
    $tables = DB::select('SHOW TABLES');
    if (count($tables) > 0) {
        echo "   Found " . count($tables) . " tables:\n";
        $count = 0;
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "   - " . $tableName . "\n";
            if (++$count >= 10) {
                echo "   ... and " . (count($tables) - 10) . " more\n";
                break;
            }
        }
    } else {
        echo "   No tables found in database.\n";
    }
    
    echo "\n✓ All tests passed!\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "\nFull error:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
