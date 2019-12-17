<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Command;

use KnotLib\DataStore\Exception\DatastoreException;
use KnotLib\DataStoreService\DataStoreComponentTrait;
use KnotLib\DataStoreService\DataStoreStringTrait;
use KnotLib\Service\Exception\ComponentNotImplementedException;
use KnotLib\Service\Exception\StringNotFoundException;
use KnotLib\Service\Exception\StringTypeException;
use KnotLib\Service\Exception\ComponentNotFoundException;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Service\Exception\ServiceNotFoundException;

use KnotPhp\Module\KnotDataStoreService\KnotDataStoreServiceModule;

use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\CommandExecutionException;
use KnotPhp\DataStore\Tools\Database\Driver;
use KnotPhp\DataStore\Tools\Database\Engine\MySQL\MySQLDatabaseEngine;


final class TableModelCreateCommand extends AbstractCommand implements CommandInterface
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
            'command_id' => 'db:generate:model',
            'aliases' => [
                'db:gen:model',
            ],
            'class_root' => dirname(__DIR__),
            'class_name' => TableModelCreateCommand::class,
            'class_base' => 'Calgamo\\DataStore\\Tools\\Command\\',
            'ordered_args' => ['table'],
            'named_args' => [
                '--app' => 'app',
                '-a' => 'app',
            ],
            'command_help' => [
                'calgamo db:generate:model table [-a|--app app]',
                'calgamo db:gen:model table [-a|--app app]',
            ],
        ]);
    }

    /**
     * Returns required modules by command
     *
     * @return array          list of class names(FQCN)
     */
    public function getRequiredModules() : array
    {
        return [
            KnotDataStoreServiceModule::class,
        ];
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
                break;
        }

        $table_describer = $engine->describeTable($table);

        $path = $this->getRuntimeFileSystem()->getDirectory(Dir::SRC);

        $generated_class = $engine->getTableModelClassGenerator()->generate($table_describer, $path, $app);

        $io->output('Generated table model class: ');
        $io->output('  ' . $generated_class);

        $logger->debug('Generated table model class: ' . $generated_class);

        return 0;
    }

}