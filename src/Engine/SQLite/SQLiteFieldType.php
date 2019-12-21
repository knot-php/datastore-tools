<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\SQLite;

final class SQLiteFieldType
{
    //===============================
    // NULL
    //===============================
    const NULL     = 0;

    //===============================
    // Integer types
    //===============================
    const INTEGER     = 100;

    //===============================
    // String types
    //===============================
    const TEXT        = 200;

    //===============================
    // Float types
    //===============================
    const REAL        = 400;

    //===============================
    // Blob
    //===============================
    const BLOB        = 500;

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
            // NULL
            //===============================
            'NULL' => self::NULL,

            //===============================
            // Integer types
            //===============================
            'INTEGER' => self::INTEGER,

            //===============================
            // String types
            //===============================
            'TEXT' => self::TEXT,

            //===============================
            // Float types
            //===============================
            'REAL' => self::REAL,

            //===============================
            // Blob
            //===============================
            'BLOB' => self::BLOB,
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
            // NULL
            //===============================
            self::NULL => 'NULL',

            //===============================
            // Integer types
            //===============================
            self::INTEGER => 'INTEGER',

            //===============================
            // String types
            //===============================
            self::TEXT => 'TEXT',

            //===============================
            // Float types
            //===============================
            self::REAL => 'REAL',

            //===============================
            // Blob
            //===============================
            self::BLOB => 'BLOB',
        ];
        return $map[$type] ?? '-';
    }
}