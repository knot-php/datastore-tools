<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Command;

use KnotLib\DataStore\Exception\DatastoreException;
use KnotLib\DataStoreService\DataStoreComponentTrait;
use KnotLib\DataStoreService\DataStoreStringTrait;
use KnotLib\Service\Exception\StringNotFoundException;
use KnotLib\Service\Exception\StringTypeException;
use KnotLib\Service\Exception\ComponentNotImplementedException;
use KnotLib\Service\Exception\ComponentNotFoundException;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Service\Exception\ServiceNotFoundException;

use KnotPhp\DataStoreTools\Engine\SQLite\SQLiteDatabaseEngine;
use KnotPhp\DataStoreTools\Module\DataStoreToolsServiceModule;
use KnotPhp\Module\KnotDataStoreService\KnotDataStoreServiceModule;

use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Exception\CommandExecutionException;
use KnotPhp\DataStoreTools\Driver;
use KnotPhp\DataStoreTools\Engine\MySQL\MySQLDatabaseEngine;

final class EntityCreateCommand extends AbstractCommand implements CommandInterface
{
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
            'command_id' => 'db:generate:entity',
            'aliases' => [
                'db:gen:ent',
            ],
            'class_root' => dirname(__DIR__),
            'class_name' => EntityCreateCommand::class,
            'class_base' => 'Calgamo\\DataStoreTools\\Command\\',
            'required' => [
                KnotDataStoreServiceModule::class,
                DataStoreToolsServiceModule::class,
            ],
            'ordered_args' => ['table'],
            'named_args' => [
                '--app' => 'app',
                '-a' => 'app',
            ],
            'command_help' => [
                'calgamo db:generate:entity table [-a|--app app]',
                'calgamo db:gen:ent table [-a|--app app]',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ComponentNotFoundException
     * @throws ComponentNotImplementedException
     * @throws ServiceNotFoundException
     * @throws DatastoreException
     * @throws StringNotFoundException
     * @throws StringTypeException
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $table = $args['table'] ?? '';
        $app = $args['app'] ?? 'MyApp';

        $logger = $this->getLoggerService();

        $logger->debug('args: ' . print_r($args, true));

        if (empty($table)){
            throw new CommandExecutionException($this->getCommandId(), 'Empty table is specified.');
        }

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

        $path = $this->getRuntimeFileSystem()->getDirectory(Dir::SRC);

        $generated_class = $engine->getEntityClassGenerator()->generate($table_describer, $path, $app);

        $io->output('Generated entity class: ');
        $io->output('  ' . $generated_class);

        $logger->debug('Generated entity class: ' . $generated_class);

        return 0;
    }

}