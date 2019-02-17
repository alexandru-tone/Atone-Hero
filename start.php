<?php

// Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

$settings = parse_ini_file(__DIR__ . '/settings.ini', true);
$app = new Atone\Battle\Battle($settings);
$app->dispatch();