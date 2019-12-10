<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database\Engine\MySQL;

use KnotLib\DataStore\Storage\Database\DatabaseConnection;
use KnotLib\DataStore\Exception\DatastoreException;

use KnotPhp\DataStore\Tools\Database\DatabaseEngineInterface;
use KnotPhp\DataStore\Tools\Database\EntityClassGeneratorInterface;
use KnotPhp\DataStore\Tools\Database\FieldDescriber;
use KnotPhp\DataStore\Tools\Database\RepositoryClassGeneratorInterface;
use KnotPhp\DataStore\Tools\Database\TableDescriber;
use KnotPhp\DataStore\Tools\Database\TableDescriberInterface;
use KnotPhp\DataStore\Tools\Database\TableModelClassGeneratorInterface;

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
    public function describeTable(string $table): TableDescriberInterface
    {
        $fields = $this->connection->sql('DESCRIBE ' . $table)->findAll();

        $field_descs = [];
        foreach($fields as $fld){
            $field_descs[] = FieldDescriber::fromArray($fld);
        }

        return new TableDescriber($table, $field_descs);
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