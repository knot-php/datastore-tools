<?php
use Stk2k\Util\StringUtil;
use KnotPhp\DataStore\Tools\Database\TableDescriberInterface;

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
    return null;
}
function get_entity_class_name(string $table_name){
    return get_base_class_name($table_name) . 'Entity';
}
function get_base_class_name(string $table_name){
    return StringUtil::pascalize($table_name);
}

$table_name = $table_desc->getTableName();
$entity_class_name = get_entity_class_name($table_name);
?>
namespace <?php echo $app; ?>\<?php echo str_replace('.', '\\', $sub_namespace); ?>;

use KnotLib\DataStore\Entity\ObjectEntity;

class <?php echo $entity_class_name; ?> extends ObjectEntity
{
<?php foreach($table_desc->getFields() as $field): ?>
    public $<?php echo $field->getFieldName(); ?>;
<?php endforeach; ?>

    /**
     * Creates new entity for insert
     *
     * @param array $values
     *
     * @return <?php echo $entity_class_name, PHP_EOL; ?>
     */
    public static function newEntity(array $values = []) : <?php echo $entity_class_name, PHP_EOL; ?>
    {
        $defaults = [
        ];
        $values = array_merge($defaults, $values);
        return new self($values);
    }
}