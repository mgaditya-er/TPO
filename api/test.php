<?php
include('dbcon.php');

$reference = $database->getReference('status');
echo $reference->getValue();

?>
<h1>HEllo </h1>