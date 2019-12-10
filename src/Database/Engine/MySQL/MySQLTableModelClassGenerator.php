<?php
declare(strict_types=1);

namespace KnotPhp\DataStore\Tools\Database\Engine\MySQL;

use Stk2k\Util\StringUtil;
use Stk2k\File\File;
use Stk2k\File\Exception\MakeDirectoryException;

use KnotLib\Kernel\FileSystem\Dir;

use KnotPhp\DataStore\Tools\FileSystem\DataStoreToolsFileSystemFactory;
use KnotPhp\DataStore\Tools\Database\TableDescriberInterface;
use KnotPhp\DataStore\Tools\Database\TableModelClassGeneratorInterface;

final class MySQLTableModelClassGenerator implements TableModelClassGeneratorInterface
{
    const DEFAULT_TABLEMODEL_SUB_NAMESPACE = 'Data.TableModel';
    const DS = DIRECTORY_SEPARATOR;

    /**
     * {@inheritDoc}
     *
     * @throws MakeDirectoryException
     */
    public function generate(TableDescriberInterface $table_desc, string $path, string $app, string $sub_namespace = null): string
    {
        if (!$sub_namespace){
            $sub_namespace = self::DEFAULT_TABLEMODEL_SUB_NAMESPACE;
        }
        else{
            $sub_namespace = str_replace('\\', '.', $sub_namespace);
            $sub_namespace = trim($sub_namespace, '.');
        }

        // determin table model class name
        $table_model_class = StringUtil::pascalize($table_desc->getTableName()) . 'TableModel.php';

        // determin real path
        $path .= self::DS . implode(self::DS, explode('.', $sub_namespace)) . self::DS . $table_model_class;

        $path = str_replace('/', self::DS, $path);

        // make directory if not exists
        (new File($path))->getParent()->makeDirectory();

        // read table model template
        $template_file = DataStoreToolsFileSystemFactory::createFileSystem()->getFile(Dir::TEMPLATE, 'table_model.tpl.php');

        ob_start();
        /** @noinspection PhpIncludeInspection */
        require $template_file;
        $content = ob_get_clean();

        $code = '<?php' . PHP_EOL . $content;

        file_put_contents($path, $code);

        return $path;
    }
}