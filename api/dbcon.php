<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('tpo-fl-firebase-adminsdk-fbsvc-55b03020b8.json')
    ->withDatabaseUri('https://tpo-fl-default-rtdb.firebaseio.com/');

    $database = $factory->createDatabase();

?>