<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database;

use KnotLib\DataStore\Exception\DatastoreException;

interface DatabaseEngineInterface
{
    /**
     * Describe table
     *
     * @param string $table
     *
     * @return TableDescriberInterface
     *
     * @throws DatastoreException
     */
    public function describeTable(string $table) : TableDescriberInterface;

    /**
     * Returns table model class generator
     *
     * @return TableModelClassGeneratorInterface
     */
    public function getTableModelClassGenerator() : TableModelClassGeneratorInterface;

    /**
     * Returns repository class generator
     *
     * @return RepositoryClassGeneratorInterface
     */
    public function getRepositoryClassGenerator() : RepositoryClassGeneratorInterface;

    /**
     * Returns entity class generator
     *
     * @return EntityClassGeneratorInterface
     */
    public function getEntityClassGenerator() : EntityClassGeneratorInterface;
}