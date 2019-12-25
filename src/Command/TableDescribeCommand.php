<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Command;

use KnotLib\DataStore\Exception\DatastoreException;
use KnotLib\DataStoreService\Util\DataStoreComponentTrait;
use KnotLib\DataStoreService\Util\DataStoreStringTrait;
use KnotLib\Service\Exception\ComponentNotImplementedException;
use KnotLib\Service\Exception\StringNotFoundException;
use KnotLib\Service\Exception\ComponentNotFoundException;
use KnotLib\Service\Exception\StringTypeException;

use KnotPhp\DataStoreTools\Engine\SQLite\SQLiteDatabaseEngine;
use KnotPhp\DataStoreTools\Module\DataStoreToolsServiceModule;
use KnotPhp\Module\KnotDataStoreService\KnotDataStoreServiceModule;

use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\CommandExecutionException;
use KnotPhp\DataStoreTools\Driver;
use KnotPhp\DataStoreTools\Engine\MySQL\MySQLDatabaseEngine;


final class TableDescribeCommand extends AbstractCommand implements CommandInterface
{
    const SEPARATOR = '-----------------------------------------------------------------------------------------';

    use DataStoreComponentTrait;
    use DataStoreStringTrait;

    /**
     * @return string
     */
    public static function getCommandId(): string
    {
        return 'db:generate:model';
    }

    public static function getDescriptor(): CommandDescriptor
    {
        return new CommandDescriptor([
            'command_id' => 'db:describe:table',
            'aliases' => [
                'db:desc:table',
            ],
            'class_root' => dirname(__DIR__),
            'class_name' => TableDescribeCommand::class,
            'class_base' => 'Calgamo\\DataStoreTools\\Command\\',
            'required' => [
                KnotDataStoreServiceModule::class,
                DataStoreToolsServiceModule::class,
            ],
            'ordered_args' => ['table'],
            'named_args' => [],
            'command_help' => [
                'calgamo db:describe:table table',
                'calgamo db:desc:table table',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ComponentNotFoundException
     * @throws ComponentNotImplementedException
     * @throws DatastoreException
     * @throws StringNotFoundException
     * @throws StringTypeException
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $table = $args['table'] ?? '';

        if (empty($table)){
            throw new CommandExecutionException($this->getCommandId(), 'Empty table is specified.');
        }

        $io->output('TABLE: ' . $table);

        $driver = $this->getDatabaseDriver($this->getContainer());
        $conn = $this->getConnection($this->getContainer());

        $engine = null;
        switch($driver)
        {
            case Driver::MYSQL:
                $engine = new MySQLDatabaseEngine($conn);
                break;

            case Driver::SQLITE:
                $engine = new SQLiteDatabaseEngine($conn);
                break;
        }

        $table_describer = $engine->getTableDescriber($table);

        $table_describer->output($io);

        return 0;
    }

}