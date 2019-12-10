<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database;

interface RepositoryClassGeneratorInterface
{
    /**
     * Generate repository
     *
     * @param TableDescriberInterface $table_desc
     * @param string $path
     * @param string $app
     * @param string $sub_namespace
     *
     * @return string       generated class(FQCN)
     */
    public function generate(TableDescriberInterface $table_desc, string $path, string $app, string $sub_namespace = null) : string;
}