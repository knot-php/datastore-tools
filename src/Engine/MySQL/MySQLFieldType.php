<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\MySQL;

final class MySQLFieldType
{
    //===============================
    // Integer types
    //===============================
    const INT         = 100;
    const LONG        = 101;

    //===============================
    // String types
    //===============================
    const STRING      = 200;
    const TEXT        = 201;
    const VARCHAR     = 202;

    //===============================
    // Date types
    //===============================
    const DATE        = 300;
    const DATETIME    = 301;

    //===============================
    // Float types
    //===============================
    const FLOAT       = 400;
    const DOUBLE      = 401;
    const REAL        = 402;

    /**
     * Returns field type
     *
     * @param string $str
     *
     * @return int
     */
    public static function fromString(string $str) : int
    {
        $map = [
            //===============================
            // Integer types
            //===============================
            'int' => self::INT,

            //===============================
            // String types
            //===============================
            'varchar' => self::VARCHAR,

            //===============================
            // Date types
            //===============================
            'date' => self::DATE,
            'datetime' => self::DATETIME,

            //===============================
            // Float types
            //===============================
            'float' => self::FLOAT,
        ];
        return $map[$str] ?? 0;
    }

    /**
     * Returns field type string
     *
     * @param int $type
     *
     * @return string
     */
    public static function toString(int $type) : string
    {
        $map = [
            //===============================
            // Integer types
            //===============================
            self::INT => 'int',

            //===============================
            // String types
            //===============================
            self::VARCHAR => 'varchar',

            //===============================
            // Date types
            //===============================
            self::DATE => 'date',
            self::DATETIME => 'datetime',

            //===============================
            // Float types
            //===============================
            self::FLOAT => 'float',
        ];
        return $map[$type] ?? '-';
    }
}