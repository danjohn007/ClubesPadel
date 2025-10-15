<?php
/**
 * Test Routes - Quick verification script
 * Run this to check if all controllers and methods exist
 */

require_once __DIR__ . '/config/config.php';

$tests = [
    // SuperAdmin routes
    ['controller' => 'SuperadminController', 'method' => 'payments'],
    
    // Admin routes - Reservations
    ['controller' => 'ReservationsController', 'method' => 'create'],
    
    // Admin routes - Finances
    ['controller' => 'FinancesController', 'method' => 'createIncome'],
    ['controller' => 'FinancesController', 'method' => 'createExpense'],
    ['controller' => 'FinancesController', 'method' => 'income'],
    ['controller' => 'FinancesController', 'method' => 'expenses'],
    
    // Courts and Tournaments - Fixed methods
    ['controller' => 'CourtsController', 'method' => 'viewCourt'],
    ['controller' => 'TournamentsController', 'method' => 'viewTournament'],
    
    // New controllers
    ['controller' => 'UsersController', 'method' => 'index'],
    ['controller' => 'NotificationsController', 'method' => 'index'],
    ['controller' => 'SettingsController', 'method' => 'index'],
    ['controller' => 'ProfileController', 'method' => 'index'],
    ['controller' => 'ReportsController', 'method' => 'index'],
];

echo "Testing Controller Methods:\n";
echo str_repeat("=", 60) . "\n\n";

$passed = 0;
$failed = 0;

foreach ($tests as $test) {
    $controllerFile = APP_PATH . '/Controllers/' . $test['controller'] . '.php';
    
    if (!file_exists($controllerFile)) {
        echo "❌ FAIL: {$test['controller']} - File not found\n";
        $failed++;
        continue;
    }
    
    require_once $controllerFile;
    $controllerClass = 'Controllers\\' . $test['controller'];
    
    if (!class_exists($controllerClass)) {
        echo "❌ FAIL: {$test['controller']} - Class not found\n";
        $failed++;
        continue;
    }
    
    if (!method_exists($controllerClass, $test['method'])) {
        echo "❌ FAIL: {$test['controller']}::{$test['method']}() - Method not found\n";
        $failed++;
        continue;
    }
    
    echo "✅ PASS: {$test['controller']}::{$test['method']}()\n";
    $passed++;
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Results: {$passed} passed, {$failed} failed\n";

if ($failed === 0) {
    echo "\n🎉 All tests passed!\n";
    exit(0);
} else {
    echo "\n⚠️  Some tests failed. Please review.\n";
    exit(1);
}
