<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Command\Provider;

use KnotPhp\Command\Command\CommandDescriptorProviderInterface;
use KnotPhp\DataStoreTools\Command\EntityCreateCommand;
use KnotPhp\DataStoreTools\Command\RepositoryCreateCommand;
use KnotPhp\DataStoreTools\Command\TableDescribeCommand;
use KnotPhp\DataStoreTools\Command\TableModelCreateCommand;

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