<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Exception;

use Throwable;

class FieldNotFoundException extends DataStoreToolsException
{
    /**
     * FieldNotFoundException constructor.
     *
     * @param string $field
     * @param string $table
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(string $field, string $table, int $code = 0, Throwable $prev = null)
    {
        $message = sprintf('Field(%s) is not found on table(%s)', $field, $table);
        parent::__construct($message, $code, $prev);
    }
}

