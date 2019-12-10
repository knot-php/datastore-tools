<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\FileSystem;

use KnotLib\Kernel\FileSystem\FileSystemFactoryInterface;
use KnotLib\Kernel\FileSystem\FileSystemInterface;

final class DataStoreToolsFileSystemFactory implements FileSystemFactoryInterface
{
    public static function createFileSystem(): FileSystemInterface
    {
        $base_dir = dirname(dirname(__DIR__));
        return new DataStoreToolsFileSystem($base_dir);
    }

}