<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\SQLite;

use KnotLib\DataStore\Storage\Database\DatabaseConnection;
use KnotPhp\DataStoreTools\DatabaseEngineInterface;
use KnotPhp\DataStoreTools\EntityClassGeneratorInterface;
use KnotPhp\DataStoreTools\RepositoryClassGeneratorInterface;
use KnotPhp\DataStoreTools\TableDescriberInterface;
use KnotPhp\DataStoreTools\TableModelClassGeneratorInterface;

final class SQLiteDatabaseEngine implements DatabaseEngineInterface
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
     */
    public function getTableDescriber(string $table): TableDescriberInterface
    {
        $fields = $this->connection->sql("select sql from sqlite_master where tbl_name ='{$table}'")->findAll();

        $create_table = $fields[0]['sql'];
        $create_table = str_replace('"', '', $create_table);
        $create_table = str_replace("\t", ' ', $create_table);

        $regex = "/^CREATE TABLE {$table} \(([\w\s,]+)\)$/m";

        preg_match($regex, $create_table, $matches);

        $fields = str_replace("\r", "\n", $matches[1]);

        $fields = array_filter(explode("\n", $fields));

        $field_descs = [];
        foreach($fields as $fld){
            $field_descs[] = SQLiteFieldDescriber::fromString($fld);
        }

        return new SQLiteTableDescriber($table, $field_descs);
    }

    public function getTableModelClassGenerator(): TableModelClassGeneratorInterface
    {
        return new SQLiteTableModelClassGenerator();
    }

    public function getRepositoryClassGenerator(): RepositoryClassGeneratorInterface
    {
        return new SQLiteRepositoryClassGenerator();
    }

    public function getEntityClassGenerator(): EntityClassGeneratorInterface
    {
        return new SQLiteEntityClassGenerator();
    }

}