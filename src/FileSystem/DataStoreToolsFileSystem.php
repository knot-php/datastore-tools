<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\FileSystem;

use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\AbstractFileSystem;
use KnotLib\Kernel\FileSystem\Dir;

final class DataStoreToolsFileSystem extends AbstractFileSystem implements FileSystemInterface
{
    /** @var string */
    private $base_dir;

    /**
     * DataStoreToolsFileSystem constructor.
     *
     * @param string $base_dir
     */
    public function __construct(string $base_dir)
    {
        $this->base_dir = $base_dir;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectory(int $dir): string
    {
        $map = [
            Dir::TEMPLATE => $this->base_dir . '/template',
        ];
        return $map[$dir] ?? '';
    }
}