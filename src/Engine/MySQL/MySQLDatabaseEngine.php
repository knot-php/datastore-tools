<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\MySQL;

use KnotLib\DataStore\Storage\Database\DatabaseConnection;
use KnotLib\DataStore\Exception\DatastoreException;

use KnotPhp\DataStoreTools\DatabaseEngineInterface;
use KnotPhp\DataStoreTools\EntityClassGeneratorInterface;
use KnotPhp\DataStoreTools\RepositoryClassGeneratorInterface;
use KnotPhp\DataStoreTools\TableDescriberInterface;
use KnotPhp\DataStoreTools\TableModelClassGeneratorInterface;

final class MySQLDatabaseEngine implements DatabaseEngineInterface
{
    /** @var DatabaseConnection */
    private $connection;

    /**
     * MySQLDatabaseEngine constructor.
     *
     * @param DatabaseConnection $connection
     */
    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     *
     * @throws DatastoreException
     */
    public function getTableDescriber(string $table): TableDescriberInterface
    {
        $fields = $this->connection->sql('DESCRIBE ' . $table)->findAll();

        $field_descs = [];
        foreach($fields as $fld){
            $field_descs[] = MySQLFieldDescriber::fromArray($fld);
        }

        return new MySQLTableDescriber($table, $field_descs);
    }

    /**
     * {@inheritDoc}
     */
    public function getTableModelClassGenerator() : TableModelClassGeneratorInterface
    {
        return new MySQLTableModelClassGenerator();
    }

    /**
     * {@inheritDoc}
     */
    public function getRepositoryClassGenerator() : RepositoryClassGeneratorInterface
    {
        return new MySQLRepositoryClassGenerator();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityClassGenerator() : EntityClassGeneratorInterface
    {
        return new MySQLEntityClassGenerator();
    }
}