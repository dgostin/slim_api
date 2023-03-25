<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../MainApp.php';

$app = new MainApp();
$app->run();
