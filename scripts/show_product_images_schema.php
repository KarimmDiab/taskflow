<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pdo = DB::connection()->getPdo();
$stmt = $pdo->query('SHOW CREATE TABLE product_images');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo $row['Create Table'] . PHP_EOL;
