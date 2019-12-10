<?php
require_once __DIR__. '/include/init.php';

use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\DataStore\Tools\Command\Provider\DataStoreToolsCommandProvider;
use KnotPhp\DataStore\Tools\Demo\DemoFileSystem;

try{
    $fs = new DemoFileSystem(__DIR__);
    $desc_s = new CommandDescriptorService($fs);

    $descriptor_list = DataStoreToolsCommandProvider::provide();

    $total = 0;
    foreach($descriptor_list as $descriptor){
        $descriptor_path = $desc_s->generateCommandDescriptor($descriptor);

        echo sprintf('Generated descriptor: [%s]', basename($descriptor_path)) . PHP_EOL;
        $total ++;
    }

    echo 'Generated total: ' . $total . PHP_EOL;
}
catch(Exception $e)
{
    echo $e->getMessage();
}
