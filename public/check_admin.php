<?php
require_once '../app/Core/Database.php';

echo "<h1>Admin User Check</h1>";

try {
    $db = App\Core\Database::getInstance();
    $stmt = $db->prepare('SELECT id, email, role FROM users WHERE role = ?');
    $stmt->execute(['admin']);
    $admins = $stmt->fetchAll();
    
    if (empty($admins)) {
        echo "<p>No admin users found. Creating one...</p>";
        
        // Create admin user
        $stmt = $db->prepare('INSERT INTO users (email, password_hash, role, status, created_at) VALUES (?, ?, ?, ?, NOW())');
        $password = 'admin123'; // Default password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute(['admin@company.com', $hashedPassword, 'admin', 'active']);
        
        echo "<p>Admin user created:</p>";
        echo "<p><strong>Email:</strong> admin@company.com</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='/login'>Go to Login</a></p>";
    } else {
        echo "<p>Found " . count($admins) . " admin user(s):</p>";
        foreach($admins as $admin) {
            echo "<p>- " . htmlspecialchars($admin['email']) . " (ID: " . $admin['id'] . ")</p>";
        }
        echo "<p><a href='/login'>Go to Login</a></p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
