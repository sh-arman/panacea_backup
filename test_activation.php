<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Activation Code Generation Process\n";
echo "==========================================\n\n";

// Test phone number
$test_phone = "8801847068188"; // Using user with company role

try {
    echo "1. Checking if test user exists in users table:\n";
    $user = \Panacea\User::where('phone_number', $test_phone)->first();
    
    if (!$user) {
        echo "   ✗ No user found with phone number: $test_phone\n";
        echo "   Creating test user...\n";
        
        // Check what columns exist in users table
        $columns = DB::select("SHOW COLUMNS FROM users");
        echo "   Available columns: ";
        foreach ($columns as $col) {
            echo $col->Field . ", ";
        }
        echo "\n\n";
        
        echo "   Please create a user first or provide an existing phone number.\n";
        exit(1);
    }
    
    echo "   ✓ User found: ID=" . $user->id . ", Phone=" . $user->phone_number . "\n\n";
    
    // Check Sentinel user
    echo "2. Checking Sentinel authentication:\n";
    $sentinelUser = Cartalyst\Sentinel\Laravel\Facades\Sentinel::findById($user->id);
    
    if (!$sentinelUser) {
        echo "   ✗ User not found in Sentinel\n";
        exit(1);
    }
    
    echo "   ✓ Sentinel user found\n";
    echo "   Email: " . ($sentinelUser->email ?? 'N/A') . "\n";
    echo "   Phone: " . $sentinelUser->phone_number . "\n";
    
    // Check permissions
    echo "   Checking 'company' permission: ";
    if ($sentinelUser->hasAccess('company')) {
        echo "✓ Has access\n\n";
    } else {
        echo "✗ No access - This is the problem!\n";
        echo "   User needs 'company' permission to login\n\n";
        
        // Check what permissions the user has
        echo "   Current permissions:\n";
        $roles = $sentinelUser->getRoles();
        foreach ($roles as $role) {
            echo "   - Role: " . $role->name . " (slug: " . $role->slug . ")\n";
            echo "     Permissions: " . json_encode($role->permissions) . "\n";
        }
        exit(1);
    }
    
    // Test activation creation
    echo "3. Testing activation code creation:\n";
    
    // Remove expired activations
    Cartalyst\Sentinel\Laravel\Facades\Activation::removeExpired();
    echo "   ✓ Removed expired activations\n";
    
    // Check existing activations
    $existingActivations = DB::table('activations')
        ->where('user_id', $user->id)
        ->where('completed', false)
        ->get();
    
    echo "   Existing incomplete activations: " . count($existingActivations) . "\n";
    
    // Create new activation
    $activation = Cartalyst\Sentinel\Laravel\Facades\Activation::create($user);
    
    if (!$activation) {
        echo "   ✗ Failed to create activation\n";
        exit(1);
    }
    
    echo "   ✓ Activation created successfully\n";
    echo "   Full activation code: " . $activation->code . "\n";
    
    // Extract 4-digit code
    $codeActive = substr($activation->code, 0, 4);
    $codeActive = strtoupper($codeActive);
    
    echo "   4-digit code: " . $codeActive . "\n\n";
    
    // Check if it was saved in database
    echo "4. Verifying activation in database:\n";
    $savedActivation = DB::table('activations')
        ->where('user_id', $user->id)
        ->where('completed', false)
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($savedActivation) {
        echo "   ✓ Activation found in database\n";
        echo "   User ID: " . $savedActivation->user_id . "\n";
        echo "   Code: " . $savedActivation->code . "\n";
        echo "   Completed: " . ($savedActivation->completed ? 'Yes' : 'No') . "\n";
        echo "   Created at: " . $savedActivation->created_at . "\n\n";
    } else {
        echo "   ✗ Activation not found in database!\n\n";
    }
    
    // Test SMS preparation
    echo "5. SMS Message that would be sent:\n";
    $message = $codeActive . ' - Is Your Login Code For Panacea Live.';
    echo "   Message: " . $message . "\n";
    echo "   To: " . $sentinelUser->phone_number . "\n";
    echo "   URL Encoded: " . urlencode($message) . "\n\n";
    
    echo "✓ All tests completed successfully!\n";
    echo "\nNote: Actual SMS was not sent. Check sendSms() method for SMS gateway issues.\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
