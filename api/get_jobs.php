<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'sql12.freesqldatabase.com';
$dbname = 'sql12774645';
$username = 'sql12774645';
$password = 'lEp1Max33R';

try {
    // DB Connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch jobs
    $stmt = $pdo->prepare("SELECT job_id, title FROM jobs");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($jobs ?: []);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500);
}
?>
