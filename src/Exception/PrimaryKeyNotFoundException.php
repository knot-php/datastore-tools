<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Exception;

use Throwable;

class PrimaryKeyNotFoundException extends DataStoreToolsException
{
    /**
     * PrimaryKeyNotFoundException constructor.
     *
     * @param string $table
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(string $table, int $code = 0, Throwable $prev = null)
    {
        $message = sprintf('Primary key is not found on table(%s)', $table);
        parent::__construct($message, $code, $prev);
    }
}

