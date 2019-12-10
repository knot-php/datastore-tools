<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database;

use KnotPhp\DataStore\Tools\Exception\FieldNotFoundException;

interface TableDescriberInterface
{
    /**
     * Returns table name
     *
     * @return string
     */
    public function getTableName() : string;

    /**
     * Returns number of fields
     *
     * @return int
     */
    public function getFieldCount() : int;

    /**
     * Returns field describers
     *
     * @return FieldDescriberInterface[]
     */
    public function getFields() : array;

    /**
     * Returns field describer
     *
     * @param string $field
     *
     * @return FieldDescriberInterface
     *
     * @throws FieldNotFoundException
     */
    public function getField(string $field) : FieldDescriberInterface;
}