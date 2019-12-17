<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Command;

use KnotLib\DataStore\Exception\DatastoreException;
use KnotLib\DataStoreService\DataStoreComponentTrait;
use KnotLib\DataStoreService\DataStoreStringTrait;
use KnotLib\Service\Exception\ComponentNotImplementedException;
use KnotLib\Service\Exception\StringNotFoundException;
use KnotLib\Service\Exception\ComponentNotFoundException;
use KnotLib\Service\Exception\StringTypeException;

use KnotPhp\Module\KnotDataStoreService\KnotDataStoreServiceModule;

use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\CommandExecutionException;
use KnotPhp\DataStore\Tools\Database\Driver;
use KnotPhp\DataStore\Tools\Database\Engine\MySQL\MySQLDatabaseEngine;
use KnotPhp\DataStore\Tools\Database\FieldType;


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
            'class_base' => 'Calgamo\\DataStore\\Tools\\Command\\',
            'ordered_args' => ['table'],
            'named_args' => [],
            'command_help' => [
                'calgamo db:describe:table table',
                'calgamo db:desc:table table',
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
                break;
        }

        $table_describer = $engine->describeTable($table);

        $io->output(self::SEPARATOR);
        $io->output(str_pad('FIELD', 25) . str_pad('TYPE', 12) . str_pad('LENGTH', 8) . str_pad('NULL', 5), false);
        $io->output(str_pad('PK', 3) . str_pad('AI', 3) . str_pad('DEFAULT', 20));
        $io->output(self::SEPARATOR);

        foreach($table_describer->getFields() as $field)
        {
            $field_name     = $field->getFieldName();
            $type           = $field->getType();
            $length         = $field->getLength() < 0 ? '-' : $field->getLength();
            $nullable       = $field->isNullable();
            $primary_key    = $field->isPrimaryKey();
            $default        = $field->getDefaultValue();
            $auto_increment = $field->isAutoIncrement();

            $line = '';
            $line .= str_pad($field_name, 25);
            $line .= str_pad(FieldType::toString($type), 12);
            $line .= str_pad("$length", 8);
            $line .= str_pad($nullable ? 'O' : '-', 5);
            $line .= str_pad($primary_key ? 'O' : '-', 3);
            $line .= str_pad($auto_increment ? 'O' : '-', 3);
            $line .= str_pad("[$default]", 20);

            $io->output($line);
        }
        $io->output(self::SEPARATOR);

        return 0;
    }

}