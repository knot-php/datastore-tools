<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\SQLite;

use KnotPhp\DataStoreTools\FieldDescriberInterface;

final class SQLiteFieldDescriber implements FieldDescriberInterface
{
    /** @var string */
    private $field_name;

    /** @var int */
    private $type;

    /** @var bool */
    private $nullable;

    /** @var bool */
    private $primary_key;

    /** @var mixed */
    private $default_value;

    /** @var bool */
    private $auto_increment;

    /** @var bool */
    private $unique;

    /**
     * MySQLFieldDescriber constructor.
     *
     * @param string $field_name
     * @param int $type
     * @param bool $nullable
     * @param bool $primary_key
     * @param $default_value
     * @param bool $auto_increment
     */
    public function __construct(string $field_name, int $type, bool $nullable, bool $primary_key, $default_value, bool $auto_increment, bool $unique)
    {
        $this->field_name     = $field_name;
        $this->type           = $type;
        $this->nullable       = $nullable;
        $this->primary_key    = $primary_key;
        $this->default_value  = $default_value;
        $this->auto_increment = $auto_increment;
        $this->unique         = $unique;
    }

    /**
     * Create from string
     *
     * @param string $field
     *
     * @return static
     */
    public static function fromString(string $field) : self
    {
        $field = trim($field, " ,");
        $props = explode(" ", $field);

        $field_name = array_shift($props);
        $type = array_shift($props);

        $type = SQLiteFieldType::fromString($type);

        $not_null = false;
        $primary_key = false;
        $default = null;
        $auto_increment = false;
        $unique = false;
        while(!empty($props)){
            $keyword = array_shift($props);

            if ($keyword === 'NOT'){
                if (!empty($props)){
                    $keyword = array_shift($props);
                    if ($keyword === 'NULL'){
                        $not_null = true;
                    }
                }
            }
            else if ($keyword === 'PRIMARY'){
                if (!empty($props)){
                    $keyword = array_shift($props);
                    if ($keyword === 'KEY'){
                        $primary_key = true;
                    }
                }
            }
            else if ($keyword === 'DEFAULT'){
                if (!empty($props)){
                    $default = array_shift($props);
                }
            }
            else if ($keyword === 'AUTOINCREMENT'){
                $auto_increment = true;
            }
            else if ($keyword === 'UNIQUE'){
                $unique = true;
            }
        }

        return new self($field_name, $type, !($not_null), $primary_key, $default, $auto_increment, $unique);
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

    /**
     * {@inheritDoc}
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

}