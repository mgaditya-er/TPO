<?php
include('dbcon.php');

$reference = $database->getReference('status');
$status = $reference->getValue();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Status Page</title>
</head>
<body>
  <h1>Status from Firebase:</h1>
  <div><?php echo $status; ?></div>
</body>
</html>
