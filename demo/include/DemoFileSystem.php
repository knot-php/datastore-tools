<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Demo;

use KnotPhp\Command\FileSystem\CommandFileSystem;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\Dir;

final class DemoFileSystem extends CommandFileSystem implements FileSystemInterface
{
    /** @var string */
    private $base_dir;

    /**
     * DemoFileSystem constructor.
     *
     * @param string $base_dir
     */
    public function __construct(string $base_dir)
    {
        parent::__construct();

        $this->base_dir = $base_dir;
    }

    public function getDirectory(int $dir): string
    {
        $map = [
            Dir::CACHE => $this->base_dir . '/cache',
            Dir::SRC => $this->base_dir . '/src',
            Dir::COMMAND => $this->base_dir . '/command',
            Dir::DATA => $this->base_dir . '/data',
        ];
        return $map[$dir] ?? parent::getDirectory($dir);
    }
}