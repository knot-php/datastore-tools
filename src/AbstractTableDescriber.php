<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools;

use KnotPhp\DataStoreTools\Exception\FieldNotFoundException;

abstract class AbstractTableDescriber implements TableDescriberInterface
{
    /** @var string */
    private $table_name;

    /** @var FieldDescriberInterface[] */
    private $fields;

    /**
     * MySQLTableDescriber constructor.
     *
     * @param string $table_name
     * @param FieldDescriberInterface[] $fields
     */
    public function __construct(string $table_name, array $fields)
    {
        $this->table_name = $table_name;
        $this->fields = $fields;
    }

    /**
     * {@inheritDoc}
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldCount(): int
    {
        return count($this->fields);
    }

    /**
     * {@inheritDoc}
     */
    public function getFields(): array
    {
       return $this->fields;
    }

    /**
     * {@inheritDoc}
     *
     * @throws FieldNotFoundException
     */
    public function getField(string $field): FieldDescriberInterface
    {
        if (!isset($this->fields[$field])){
            throw new FieldNotFoundException($field, $this->table_name);
        }
        return $this->fields[$field];
    }

}