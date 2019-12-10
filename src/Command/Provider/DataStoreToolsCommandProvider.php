<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Command\Provider;

use KnotPhp\Command\Command\CommandDescriptorProviderInterface;
use KnotPhp\DataStore\Tools\Command\EntityCreateCommand;
use KnotPhp\DataStore\Tools\Command\RepositoryCreateCommand;
use KnotPhp\DataStore\Tools\Command\TableDescribeCommand;
use KnotPhp\DataStore\Tools\Command\TableModelCreateCommand;

final class DataStoreToolsCommandProvider implements CommandDescriptorProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function provide(): array
    {
        return [
            TableDescribeCommand::getDescriptor(),
            TableModelCreateCommand::getDescriptor(),
            RepositoryCreateCommand::getDescriptor(),
            EntityCreateCommand::getDescriptor(),
        ];
    }
}