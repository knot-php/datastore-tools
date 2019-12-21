<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools;

interface FieldDescriberInterface
{
    /**
     * Returns field name
     *
     * @return string
     */
    public function getFieldName() : string;

    /**
     * Returns field type
     *
     * @return int
     */
    public function getType() : int;

    /**
     * Returns if field can be null
     *
     * @return bool
     */
    public function isNullable() : bool;

    /**
     * Returns whether field is a primary key or not
     *
     * @return bool
     */
    public function isPrimaryKey() : bool;

    /**
     * Returns default value
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Returns whether field is automatically incremented
     *
     * @return bool
     */
    public function isAutoIncrement() : bool;
}