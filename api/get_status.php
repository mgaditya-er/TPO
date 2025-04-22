<?php
// get_status.php
include('dbcon.php');

$reference = $database->getReference('status');
$status = $reference->getValue();

header('Content-Type: application/json');
echo json_encode(['status' => $status]);
