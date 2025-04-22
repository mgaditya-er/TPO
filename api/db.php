<?php
$host = 'sql12.freesqldatabase.com';
$dbname = 'sql12774645';
$username = 'sql12774645';
$password = 'lEp1Max33R';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
