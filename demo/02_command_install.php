<?php
require_once __DIR__ . '/include/init.php';

use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\AliasDbFileService;

use KnotPhp\DataStoreTools\Command\Provider\DataStoreToolsCommandProvider;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\DataStoreTools\Demo\DemoFileSystem;

try{
    $fs = new DemoFileSystem(__DIR__);
    $desc_s = new CommandDescriptorService($fs);
    $db_file_s = new CommandDbFileService($fs);
    $alias_db = new AliasDbFileService($fs);

    $descriptor_list = DataStoreToolsCommandProvider::provide();

    $total = 0;
    foreach($descriptor_list as $descriptor){
        /** @var CommandDescriptor $descriptor */
        $db_file_s->setDesciptor($descriptor->getCommandId(), $descriptor);

        $total ++;
    }

    $db_file_s->save();

    $alias_db->importAlias($db_file_s);
    $alias_db->save();

}
catch(Exception $e)
{
    echo $e->getMessage();
}

