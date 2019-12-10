<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database\Engine\MySQL;

use Stk2k\Util\StringUtil;
use Stk2k\File\File;
use Stk2k\File\Exception\MakeDirectoryException;

use KnotLib\Kernel\FileSystem\Dir;

use KnotPhp\DataStore\Tools\FileSystem\DataStoreToolsFileSystemFactory;
use KnotPhp\DataStore\Tools\Database\EntityClassGeneratorInterface;
use KnotPhp\DataStore\Tools\Database\TableDescriberInterface;

final class MySQLEntityClassGenerator implements EntityClassGeneratorInterface
{
    const DEFAULT_ENTITY_SUB_NAMESPACE = 'Data.Entity';
    const DS = DIRECTORY_SEPARATOR;

    /**
     * {@inheritDoc}
     *
     * @throws MakeDirectoryException
     */
    public function generate(TableDescriberInterface $table_desc, string $path, string $app, string $sub_namespace = null): string
    {
        if (!$sub_namespace){
            $sub_namespace = self::DEFAULT_ENTITY_SUB_NAMESPACE;
        }
        else{
            $sub_namespace = str_replace('\\', '.', $sub_namespace);
            $sub_namespace = trim($sub_namespace, '.');
        }

        // determin entity class name
        $entity_class = StringUtil::pascalize($table_desc->getTableName()) . 'Entity.php';

        // determin real path
        $path .= self::DS . implode(self::DS, explode('.', $sub_namespace)) . self::DS . $entity_class;

        $path = str_replace('/', self::DS, $path);

        // make directory if not exists
        (new File($path))->getParent()->makeDirectory();

        // read entity template
        $template_file = DataStoreToolsFileSystemFactory::createFileSystem()->getFile(Dir::TEMPLATE, 'entity.tpl.php');

        ob_start();
        /** @noinspection PhpIncludeInspection */
        require $template_file;
        $content = ob_get_clean();

        $code = '<?php' . PHP_EOL . $content;

        file_put_contents($path, $code);

        return $path;
    }
}