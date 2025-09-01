<?php
session_start();

echo "<h1>Login Debug Test</h1>";

if ($_POST) {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    // Load required files in correct order
    require_once '../app/Core/Database.php';
    require_once '../app/Models/User.php';
    require_once '../app/Core/Auth.php';
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<h2>Attempting login with:</h2>";
    echo "<p>Email: " . htmlspecialchars($email) . "</p>";
    echo "<p>Password: " . (empty($password) ? 'EMPTY' : 'PROVIDED') . "</p>";
    
    // Test Auth::attempt
    try {
        $result = App\Core\Auth::attempt($email, $password);
        echo "<p>Auth::attempt result: " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";
        
        echo "<h2>Session after login attempt:</h2>";
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
        
        echo "<h2>Auth check after attempt:</h2>";
        echo "<p>Is authenticated: " . (App\Core\Auth::check() ? 'Yes' : 'No') . "</p>";
        
    } catch (Exception $e) {
        echo "<p>Error during login: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<h2>Login Test Form</h2>";
    echo '<form method="POST">';
    echo '<p>Email: <input type="email" name="email" value="admin@company.com" required></p>';
    echo '<p>Password: <input type="password" name="password" value="admin123" required></p>';
    echo '<p><button type="submit">Test Login</button></p>';
    echo '</form>';
}
?>
