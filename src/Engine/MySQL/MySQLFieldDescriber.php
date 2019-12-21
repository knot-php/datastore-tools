<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\MySQL;

use KnotPhp\DataStoreTools\FieldDescriberInterface;

final class MySQLFieldDescriber implements FieldDescriberInterface
{
    /** @var string */
    private $field_name;

    /** @var int */
    private $type;

    /** @var int */
    private $length;

    /** @var bool */
    private $nullable;

    /** @var bool */
    private $primary_key;

    /** @var mixed */
    private $default_value;

    /** @var bool */
    private $auto_increment;

    /**
     * MySQLFieldDescriber constructor.
     *
     * @param string $field_name
     * @param int $type
     * @param int $length
     * @param bool $nullable
     * @param bool $primary_key
     * @param $default_value
     * @param bool $auto_increment
     */
    public function __construct(string $field_name, int $type, int $length, bool $nullable, bool $primary_key, $default_value, bool $auto_increment)
    {
        $this->field_name     = $field_name;
        $this->type           = $type;
        $this->length         = $length;
        $this->nullable       = $nullable;
        $this->primary_key    = $primary_key;
        $this->default_value  = $default_value;
        $this->auto_increment = $auto_increment;
    }

    /**
     * Create from array data
     *
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data) : self
    {
        $field_name = $data['Field'];
        $type = $data['Type'];
        $null = $data['Null'];
        $key = $data['Key'];
        $default = $data['Default'];
        $extra = $data['Extra'];

        // type/length
        $length = -1;
        if (preg_match('/^([a-z]+)\(([0-9]+)\)$/', $type, $matches)){
            $type = MySQLFieldType::fromString($matches[1]);
            $length = intval($matches[2]);
        }
        else if (preg_match('/^([a-z]+)$/', $type, $matches)){
            $type = MySQLFieldType::fromString($matches[1]);
        }

        // nullable
        $nullable = $null === 'YES';

        // primary_key
        $primary_key = strpos($key, 'PRI') !== false;

        // auto increment
        $auto_increment = strpos($extra, 'auto_increment') !== false;

        return new self($field_name, $type, $length, $nullable, $primary_key, $default, $auto_increment);
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldName(): string
    {
        return $this->field_name;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * {@inheritDoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimaryKey(): bool
    {
        return $this->primary_key;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * {@inheritDoc}
     */
    public function isAutoIncrement(): bool
    {
        return $this->auto_increment;
    }

}