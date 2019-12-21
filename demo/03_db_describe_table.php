<?php
declare(strict_types=1);

require_once __DIR__. '/include/init.php';

use Stk2k\Util\Util;

use KnotLib\Console\Request\ShellRequest;
use KnotLib\Di\Container;

use KnotPhp\DataStoreTools\Demo\DemoApplication;
use KnotPhp\Command\Service\CommandExecService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandAutoloadService;
use KnotPhp\DataStoreTools\Demo\DemoFileSystemFactory;
use KnotPhp\Command\Command\DefaultConsoleIO;
use KnotPhp\Module\KnotDi\Adapter\KnotDiContainerAdapter;

try{
    $request = new ShellRequest([
        'fruits'
    ]);
    $fs = DemoFileSystemFactory::createFileSystem();
    $app = new DemoApplication($fs);

    $app->request($request);

    $command_db_s = new CommandDbFileService($fs);
    $alias_db_s = new AliasDbFileService($fs);
    //$autoload_s = new CommandAutoloadService($fs, $command_db_s);
    $io = new DefaultConsoleIO();
    $exec = new CommandExecService($fs, $app, $command_db_s, $alias_db_s, $io);

    $app->run();

    //$autoload_s->autoload();

    $io->output('Execute command:');
    $exec->executeCommand($app->di(), 'db:describe:table', 0);

}
catch(Throwable $e){
    Util::dumpException($e);
}
