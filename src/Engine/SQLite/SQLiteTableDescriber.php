<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\SQLite;

use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\DataStoreTools\AbstractTableDescriber;

final class SQLiteTableDescriber extends AbstractTableDescriber
{
    const SEPARATOR = '-----------------------------------------------------------------------------------------';

    /**
     * {@inheritDoc}
     */
    public function output(ConsoleIOInterface $io)
    {
        $io->output(self::SEPARATOR);
        $io->output(str_pad('FIELD', 25) . str_pad('TYPE', 12) . str_pad('NULL', 5), false);
        $io->output(str_pad('PK', 3) . str_pad('AI', 3) . str_pad('UQ', 3) . str_pad('DEFAULT', 20));
        $io->output(self::SEPARATOR);

        foreach($this->getFields() as $field)
        {
            /** @var SQLiteFieldDescriber $field */
            $field_name     = $field->getFieldName();
            $type           = $field->getType();
            $nullable       = $field->isNullable();
            $primary_key    = $field->isPrimaryKey();
            $default        = $field->getDefaultValue();
            $auto_increment = $field->isAutoIncrement();
            $unique         = $field->isUnique();

            $line = '';
            $line .= str_pad($field_name, 25);
            $line .= str_pad(SQLiteFieldType::toString($type), 12);
            $line .= str_pad($nullable ? 'O' : '-', 5);
            $line .= str_pad($primary_key ? 'O' : '-', 3);
            $line .= str_pad($auto_increment ? 'O' : '-', 3);
            $line .= str_pad($unique ? 'O' : '-', 3);
            $line .= str_pad("[$default]", 20);

            $io->output($line);
        }
        $io->output(self::SEPARATOR);
    }

}