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

echo "<h1>Session Expiration Debug</h1>";

echo "<h2>Session Info:</h2>";
echo "<p>Current time: " . time() . " (" . date('Y-m-d H:i:s') . ")</p>";
echo "<p>Login time: " . ($_SESSION['login_time'] ?? 'NOT SET') . " (" . (isset($_SESSION['login_time']) ? date('Y-m-d H:i:s', $_SESSION['login_time']) : 'N/A') . ")</p>";
echo "<p>Session lifetime: " . $config['session']['lifetime'] . " seconds</p>";

if (isset($_SESSION['login_time'])) {
    $timeDiff = time() - $_SESSION['login_time'];
    echo "<p>Time since login: " . $timeDiff . " seconds</p>";
    echo "<p>Session expired: " . ($timeDiff > $config['session']['lifetime'] ? 'YES' : 'NO') . "</p>";
}

require_once '../app/Core/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Core/Auth.php';

echo "<h2>Auth Check:</h2>";
echo "<p>Auth::check(): " . (App\Core\Auth::check() ? 'YES' : 'NO') . "</p>";

if (!App\Core\Auth::check()) {
    echo "<p>Authentication failed - checking why...</p>";
    
    // Manually check each condition
    echo "<p>user_id in session: " . (isset($_SESSION['user_id']) ? 'YES' : 'NO') . "</p>";
    
    if (isset($_SESSION['user_id'])) {
        // Check if session expired
        if (isset($_SESSION['login_time'])) {
            $timeDiff = time() - $_SESSION['login_time'];
            $sessionLifetime = $config['session']['lifetime'];
            echo "<p>Session timeout check: " . ($timeDiff > $sessionLifetime ? 'EXPIRED' : 'VALID') . "</p>";
            
            if ($timeDiff > $sessionLifetime) {
                echo "<p style='color: red;'>SESSION HAS EXPIRED! Time difference: {$timeDiff}s, Limit: {$sessionLifetime}s</p>";
            }
        }
    }
}
?>
