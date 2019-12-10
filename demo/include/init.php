<?php
use KnotPhp\Command\Env\EnvKey;
use KnotPhp\DataStore\Tools\Demo\DemoFileSystemFactory;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

putenv('DB_DSN=mysql:host=localhost;dbname=calgamo_db;charset=utf8');
putenv('DB_USER=root');
putenv('DB_PASS=');

putenv(EnvKey::COMMAND_FILESYSTEM_FACTORY . '=' . DemoFileSystemFactory::class);
