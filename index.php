<?php
require_once __DIR__ . '/vendor/autoload.php';

$application = new demo\Application;

$application->default = 'message';

$application->run();