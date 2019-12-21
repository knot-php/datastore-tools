<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Demo;

use KnotLib\Kernel\FileSystem\FileSystemFactoryInterface;
use KnotLib\Kernel\FileSystem\FileSystemInterface;

final class DemoFileSystemFactory implements FileSystemFactoryInterface
{
    public static function createFileSystem(): FileSystemInterface
    {
        $base_dir = dirname(__DIR__);
        return new DemoFileSystem($base_dir);
    }

}