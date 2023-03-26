<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../db/db.php';
require __DIR__ . '/../MainApp.php';

$app = new MainApp();
$app->run();
