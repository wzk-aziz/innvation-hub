<?php
// Use the same session configuration as the main application
$config = require '../config/config.php';

// Configure session like Bootstrap does
$sessionConfig = $config['session'];
ini_set('session.name', $sessionConfig['name']);
ini_set('session.gc_maxlifetime', (string)$sessionConfig['lifetime']);
ini_set('session.cookie_lifetime', (string)$sessionConfig['lifetime']);
ini_set('session.cookie_path', $sessionConfig['path']);
ini_set('session.cookie_domain', $sessionConfig['domain']);
ini_set('session.cookie_secure', $sessionConfig['secure'] ? '1' : '0');
ini_set('session.cookie_httponly', $sessionConfig['httponly'] ? '1' : '0');
ini_set('session.cookie_samesite', $sessionConfig['samesite']);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_start();

echo "<h1>Session Debug</h1>";
echo "<h2>Session Data:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h2>CSRF Token Info:</h2>";
if (isset($_SESSION['csrf_token'])) {
    echo "<p>CSRF Token exists: " . $_SESSION['csrf_token'] . "</p>";
} else {
    echo "<p>No CSRF Token found</p>";
}

echo "<h2>Config Test:</h2>";
$config = require '../config/config.php';
echo "<p>CSRF Token Name from config: " . $config['security']['csrf_token_name'] . "</p>";

echo "<h2>Auth Check:</h2>";
require_once '../app/Core/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Core/Auth.php';
echo "<p>Is authenticated: " . (App\Core\Auth::check() ? 'Yes' : 'No') . "</p>";

echo "<h3>Auth Session Details:</h3>";
echo "<p>user_id in session: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NOT SET') . "</p>";
echo "<p>user_role in session: " . (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'NOT SET') . "</p>";
echo "<p>login_time in session: " . (isset($_SESSION['login_time']) ? $_SESSION['login_time'] : 'NOT SET') . "</p>";

if (App\Core\Auth::check()) {
    $user = App\Core\Auth::user();
    echo "<p>User: " . $user['email'] . " (Role: " . $user['role'] . ")</p>";
} else {
    echo "<p>User not authenticated - checking why...</p>";
    // Let's manually check what Auth::user() returns
    try {
        $user = App\Core\Auth::user();
        echo "<p>Auth::user() returned: " . ($user ? 'User object' : 'null/false') . "</p>";
    } catch (Exception $e) {
        echo "<p>Auth::user() threw exception: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>CSRF Field Test:</h2>";
echo App\Core\Auth::csrfField();
?>
