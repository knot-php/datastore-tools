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
function get_table_model_class_name(string $table_name){
    return get_base_class_name($table_name) . 'TableModel';
}
function get_repository_class_name(string $table_name){
    return get_base_class_name($table_name) . 'Repository';
}
function get_entity_class_name(string $table_name){
    return get_base_class_name($table_name) . 'Entity';
}
function get_base_class_name(string $table_name){
    return StringUtil::pascalize($table_name);
}
function get_table_singular_form($table_name){
    if (strrpos($table_name, 'ies')) return substr($table_name, 0, -3);
    if (strrpos($table_name, 's')) return substr($table_name, 0, -1);
    return $table_name;
}

$table_name = $table_desc->getTableName();
$repository_class_name = get_repository_class_name($table_name);
$entity_class_name = get_entity_class_name($table_name);
$table_model_class_name = get_table_model_class_name($table_name);
$base_class_name = get_base_class_name($table_name);
$table_name_singular = get_table_singular_form($table_name);
?>
namespace <?php echo $app; ?>\<?php echo str_replace('.', '\\', $sub_namespace); ?>;

use CalgamoLib\DataStore\Repository\OneToOneRepository;
use CalgamoLib\DataStore\StorageInterface;
use CalgamoLib\DataStore\EntityId;
use <?php echo $app; ?>\Data\Entity\<?php echo $entity_class_name; ?>;
use <?php echo $app; ?>\Data\TableModel\<?php echo $table_model_class_name; ?>;

class <?php echo $repository_class_name; ?> extends OneToOneRepository
{
    /**
     * Repository constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        parent::__construct($storage, <?php echo $entity_class_name; ?>::class, new <?php echo $table_model_class_name; ?>(), '<?php echo $table_name; ?>');
    }

    /**
     * Get entity by id
     *
     * @param int $<?php echo $pk->getFieldName(), PHP_EOL; ?>
     *
     * @return <?php echo $entity_class_name; ?>|null
     */
    public function get(int $<?php echo $pk->getFieldName(); ?>)
    {
        $entity_id = new EntityId(['<?php echo $pk->getFieldName(); ?>' => $<?php echo $pk->getFieldName(); ?>]);
        $entity = parent::load($entity_id)->execute();

        if ($entity instanceof <?php echo $entity_class_name; ?>){
            return $entity;
        }

        return null;
    }

    /**
     * Save entity
     *
     * @param <?php echo $entity_class_name; ?> $<?php echo $table_name_singular, PHP_EOL; ?>
     *
     * @return <?php echo $entity_class_name, PHP_EOL; ?>
     *
     * @throws
     */
    public function save<?php echo $base_class_name; ?>(<?php echo $entity_class_name; ?> $<?php echo $table_name_singular; ?>) : <?php echo $entity_class_name, PHP_EOL; ?>
    {
        /** @var <?php echo $entity_class_name; ?> $<?php echo $table_name_singular; ?> */
        $<?php echo $table_name_singular; ?> = parent::save($<?php echo $table_name_singular; ?>);

        return $<?php echo $table_name_singular; ?>;
    }


    /**
     * delete entity
     *
     * @param <?php echo $entity_class_name; ?> $<?php echo $table_name_singular, PHP_EOL; ?>
     *
     * @return <?php echo $entity_class_name, PHP_EOL; ?>
     *
     * @throws
     */
    public function delete<?php echo $base_class_name; ?>(<?php echo $entity_class_name; ?> $<?php echo $table_name_singular; ?>) : <?php echo $entity_class_name, PHP_EOL; ?>
    {
        //$<?php echo $table_name_singular; ?>->deleted      = 1;

        /** @var <?php echo $entity_class_name; ?> $<?php echo $table_name_singular; ?> */
        $<?php echo $table_name_singular; ?> = parent::save($<?php echo $table_name_singular; ?>);

        return $<?php echo $table_name_singular; ?>;
    }
}