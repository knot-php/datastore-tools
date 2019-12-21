<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Exception;

use Throwable;

use KnotLib\Exception\KnotPhpException;

class DataStoreToolsException extends KnotPhpException implements DataStoreToolsExceptionInterface
{
    /**
     * DataStoreToolsException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $message, int $code = 0, Throwable $prev = null )
    {
        parent::__construct($message, $code, $prev);
    }
}

