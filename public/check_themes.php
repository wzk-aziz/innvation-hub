<?php
require_once '../app/Core/Database.php';

echo "<h1>Theme Database Check</h1>";

try {
    $db = App\Core\Database::getInstance();
    $stmt = $db->query('SELECT * FROM themes ORDER BY id');
    $themes = $stmt->fetchAll();
    
    if (empty($themes)) {
        echo "<p>No themes found. Creating sample themes...</p>";
        
        // Create sample themes
        $sampleThemes = [
            ['Développement Web', 'Technologies et frameworks pour le développement web moderne', 1],
            ['Intelligence Artificielle', 'Machine Learning, Deep Learning et applications IA', 1],
            ['Sécurité Informatique', 'Cybersécurité, pentest et protection des données', 0],
            ['Design UX/UI', 'Conception d\'interfaces utilisateur et expérience utilisateur', 1]
        ];
        
        foreach ($sampleThemes as $theme) {
            $stmt = $db->prepare('INSERT INTO themes (name, description, is_active, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute($theme);
            echo "<p>Created theme: " . htmlspecialchars($theme[0]) . "</p>";
        }
        
        echo "<p><strong>Sample themes created!</strong></p>";
    } else {
        echo "<p>Found " . count($themes) . " themes:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Active</th><th>Created</th></tr>";
        
        foreach($themes as $theme) {
            echo "<tr>";
            echo "<td>" . $theme['id'] . "</td>";
            echo "<td>" . htmlspecialchars($theme['name']) . "</td>";
            echo "<td>" . htmlspecialchars($theme['description']) . "</td>";
            echo "<td>" . ($theme['is_active'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $theme['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<p><a href='/admin/themes'>Go to Themes Management Page</a></p>";
    
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
