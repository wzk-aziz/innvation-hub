<?php
echo "<h1>Session Configuration Debug</h1>";

echo "<h2>Session Settings:</h2>";
echo "<p>Session Name: " . session_name() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Session Cookie Params: </p>";
echo "<pre>" . print_r(session_get_cookie_params(), true) . "</pre>";

echo "<h2>INI Settings:</h2>";
$iniSettings = [
    'session.name',
    'session.gc_maxlifetime', 
    'session.cookie_lifetime',
    'session.cookie_path',
    'session.cookie_domain',
    'session.cookie_secure',
    'session.cookie_httponly',
    'session.cookie_samesite',
    'session.use_strict_mode',
    'session.use_only_cookies'
];

foreach ($iniSettings as $setting) {
    echo "<p>{$setting}: " . ini_get($setting) . "</p>";
}

echo "<h2>Config File Session Settings:</h2>";
$config = require '../config/config.php';
echo "<pre>" . print_r($config['session'], true) . "</pre>";
?>
