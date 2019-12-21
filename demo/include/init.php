<?php
use KnotPhp\Command\Env\EnvKey;
use KnotPhp\DataStoreTools\Demo\DemoFileSystemFactory;

$base_dir = dirname(dirname(__DIR__));

require_once $base_dir . '/vendor/autoload.php';

//putenv('DB_DSN=sqlite:' . "{$base_dir}/db/sqlite3.db");
putenv('DB_DSN=sqlite:' . "C:\\Temp\\sqlite3.db");
putenv('DB_USER=');
putenv('DB_PASS=');

//putenv('DB_DSN=mysql:host=localhost;dbname=calgamo_db;charset=utf8');
//putenv('DB_USER=root');
//putenv('DB_PASS=');

putenv(EnvKey::COMMAND_FILESYSTEM_FACTORY . '=' . DemoFileSystemFactory::class);
