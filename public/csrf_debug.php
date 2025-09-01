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

require_once '../app/Core/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Core/Auth.php';

echo "<h1>CSRF Token Debug</h1>";

echo "<h2>Session CSRF Token:</h2>";
echo "<p>" . ($_SESSION['csrf_token'] ?? 'NOT SET') . "</p>";

echo "<h2>Auth::getCsrfToken():</h2>";
echo "<p>" . App\Core\Auth::getCsrfToken() . "</p>";

echo "<h2>Auth::csrfField() output:</h2>";
echo "<div>" . App\Core\Auth::csrfField() . "</div>";

echo "<h2>Token Name from Config:</h2>";
echo "<p>" . $config['security']['csrf_token_name'] . "</p>";

echo "<h2>Test CSRF Validation:</h2>";
$sessionToken = $_SESSION['csrf_token'] ?? '';
$generatedToken = App\Core\Auth::getCsrfToken();
echo "<p>Session token equals generated token: " . ($sessionToken === $generatedToken ? 'YES' : 'NO') . "</p>";

if ($_POST) {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    $tokenName = $config['security']['csrf_token_name'];
    $receivedToken = $_POST[$tokenName] ?? '';
    
    echo "<h2>CSRF Validation Test:</h2>";
    echo "<p>Expected token: " . $sessionToken . "</p>";
    echo "<p>Received token: " . $receivedToken . "</p>";
    echo "<p>Tokens match: " . ($sessionToken === $receivedToken ? 'YES' : 'NO') . "</p>";
    echo "<p>Auth::validateCsrfToken result: " . (App\Core\Auth::validateCsrfToken($receivedToken) ? 'VALID' : 'INVALID') . "</p>";
}

echo "<h2>Test Delete Form:</h2>";
echo '<form method="POST">';
echo App\Core\Auth::csrfField();
echo '<button type="submit">Test CSRF Token</button>';
echo '</form>';
?>
