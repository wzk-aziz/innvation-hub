<?php
// Use the same session configuration as the main application
$config = require '../config/config.php';

// Configure session like Bootstrap does
$sessionConfig = $config['session'];
ini_set('session.name', $sessionConfig['name']);
session_start();

echo "<h1>JavaScript Debug Test</h1>";

echo "<p>This page will test if JavaScript is working properly.</p>";

echo '<button id="testBtn" onclick="testClick()">Test Button</button>';
echo '<div id="result"></div>';

echo '<h2>Test Edit Button (like themes page):</h2>';
echo '<button class="edit-theme" data-theme-id="1">Edit Theme 1</button>';
echo '<div id="editResult"></div>';

echo '<script>
console.log("JavaScript test loaded");

function testClick() {
    console.log("Test button clicked");
    document.getElementById("result").innerHTML = "JavaScript is working!";
}

// Test the same setup as themes page
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM loaded");
    
    const editButtons = document.querySelectorAll(".edit-theme");
    console.log("Found edit buttons:", editButtons.length);
    
    editButtons.forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            console.log("Edit button clicked!");
            document.getElementById("editResult").innerHTML = "Edit button works!";
        });
    });
});
</script>';
?>
