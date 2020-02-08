<?php
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

use Stk2k\Util\StringUtil;

use KnotPhp\DataStoreTools\TableDescriberInterface;
use KnotPhp\DataStoreTools\Exception\PrimaryKeyNotFoundException;

/** @var string $app */
/** @var string $sub_namespace */
/** @var TableDescriberInterface $table_desc */

$pk = get_primary_key($table_desc);

function get_primary_key(TableDescriberInterface $table_desc){
    foreach($table_desc->getFields() as $field){
        if ($field->isPrimaryKey()){
            return $field;
        }
    }
    throw new PrimaryKeyNotFoundException($table_desc->getTableName());
}
function get_table_alias(string $table_name){
    $words = explode('_', StringUtil::snake($table_name));
    $heads = [];
    foreach($words as $w){
        if (!empty($w)){
            $heads[] = substr($w, 0, 1);
        }
    }
    return implode('', $heads);
}
function get_table_model_class_name(string $table_name){
    return StringUtil::pascalize($table_name) . 'TableModel';
}
?>
declare(strict_types=1);

namespace <?php echo $app; ?>\<?php echo str_replace('.', '\\', $sub_namespace); ?>;

use KnotLib\DataStore\TableModelInterface;
use KnotLib\DataStore\PrimaryKeyInterface;
use KnotLib\DataStore\PrimaryKey\IntegerSinglePrimaryKey;

class <?php echo get_table_model_class_name($table_desc->getTableName()); ?> implements TableModelInterface
{
    /**
     * Get primary key
     *
     * @return PrimaryKeyInterface
     */
    public function getPrimaryKey() : PrimaryKeyInterface
    {
        return new IntegerSinglePrimaryKey('<?php echo $pk->getFieldName(); ?>');
    }

    /**
     * Get phisical table ename
     *
     * @return string
     */
    public function getTableName() : string
    {
        return '<?php echo $table_desc->getTableName(); ?>';
    }

    /**
     * Get table name alias
     *
     * @return string
     */
    public function getAlias() : string
    {
        return '<?php echo get_table_alias($table_desc->getTableName()); ?>';
    }

    /**
     * Get select fields
     *
     * @return array
     */
    public function getSelectFields() : array
    {
        return [
<?php foreach($table_desc->getFields() as $field): ?>
            '<?php echo $field->getFieldName(); ?>',
<?php endforeach; ?>
        ];
    }

    /**
     * Get update fields
     *
     * @return array
     */
    public function getUpdateFields() : array
    {
        return [
<?php foreach($table_desc->getFields() as $field): ?>
            '<?php echo $field->getFieldName(); ?>',
<?php endforeach; ?>
        ];
    }

    /**
     * Get insert fields
     *
     * @return array
     */
    public function getInsertFields() : array
    {
        return [
<?php foreach($table_desc->getFields() as $field): ?>
            '<?php echo $field->getFieldName(); ?>',
<?php endforeach; ?>
        ];
    }
}