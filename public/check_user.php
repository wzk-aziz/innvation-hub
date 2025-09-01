<?php
require_once '../app/Core/Database.php';

echo "<h1>User Database Check</h1>";

try {
    $db = App\Core\Database::getInstance();
    $stmt = $db->prepare('SELECT id, email, password_hash, role, status, created_at FROM users WHERE email = ?');
    $stmt->execute(['admin@company.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<h2>User Found:</h2>";
        echo "<p><strong>ID:</strong> " . $user['id'] . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>Role:</strong> " . $user['role'] . "</p>";
        echo "<p><strong>Status:</strong> " . $user['status'] . "</p>";
        echo "<p><strong>Created:</strong> " . $user['created_at'] . "</p>";
        echo "<p><strong>Password Hash:</strong> " . htmlspecialchars($user['password_hash']) . "</p>";
        
        echo "<h2>Password Test:</h2>";
        $testPassword = 'admin123';
        $passwordValid = password_verify($testPassword, $user['password_hash']);
        echo "<p>Password 'admin123' valid: " . ($passwordValid ? 'YES' : 'NO') . "</p>";
        
        if (!$passwordValid) {
            echo "<h3>Updating password to 'admin123'...</h3>";
            $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
            $updateStmt = $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $updateStmt->execute([$newHash, $user['id']]);
            echo "<p>Password updated successfully!</p>";
        }
        
    } else {
        echo "<p>User not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
