<?php

declare(strict_types=1);

use App\Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';

Database::configure($config['db']);
