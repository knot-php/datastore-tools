<?php
/** @noinspection PhpUnusedParameterInspection */
require_once __DIR__. '/include/init.php';

use Stk2k\Util\Util;

use KnotPhp\DataStore\Tools\Command\TableDescribeCommand;
use KnotPhp\Command\Command\DefaultConsoleIO;
use KnotPhp\DataStore\Tools\Demo\DemoApplication;
use KnotPhp\DataStore\Tools\Demo\DemoFileSystem;

try{
    $fs = new DemoFileSystem(__DIR__);
    $app = new DemoApplication($fs);

    $app->run();

    $command = new TableDescribeCommand($app->di());
    $io = new DefaultConsoleIO();

    $args = [
        'table' => 'fruits'
    ];

    $app->installModules($command->getRequiredModules());

    $command->execute($args, $io);
}
catch(Throwable $e){
    Util::dumpException($e);
}
