<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Engine\MySQL;

use Stk2k\Util\StringUtil;
use Stk2k\File\File;
use Stk2k\File\Exception\MakeDirectoryException;

use KnotLib\Kernel\FileSystem\Dir;

use KnotPhp\DataStoreTools\FileSystem\DataStoreToolsFileSystemFactory;
use KnotPhp\DataStoreTools\RepositoryClassGeneratorInterface;
use KnotPhp\DataStoreTools\TableDescriberInterface;

final class MySQLRepositoryClassGenerator implements RepositoryClassGeneratorInterface
{
    const DEFAULT_REPOSITORY_SUB_NAMESPACE = 'Data.Repository';
    const DS = DIRECTORY_SEPARATOR;

    /**
     * {@inheritDoc}
     *
     * @throws MakeDirectoryException
     */
    public function generate(TableDescriberInterface $table_desc, string $path, string $app, string $sub_namespace = null): string
    {
        if (!$sub_namespace){
            $sub_namespace = self::DEFAULT_REPOSITORY_SUB_NAMESPACE;
        }
        else{
            $sub_namespace = str_replace('\\', '.', $sub_namespace);
            $sub_namespace = trim($sub_namespace, '.');
        }

        // determin repository class name
        $repository_class = StringUtil::pascalize($table_desc->getTableName()) . 'Repository.php';

        // determin real path
        $path .= self::DS . implode(self::DS, explode('.', $sub_namespace)) . self::DS . $repository_class;

        $path = str_replace('/', self::DS, $path);

        // make directory if not exists
        (new File($path))->getParent()->makeDirectory();

        // read repository template
        $template_file = DataStoreToolsFileSystemFactory::createFileSystem()->getFile(Dir::TEMPLATE, 'repository.tpl.php');

        ob_start();
        /** @noinspection PhpIncludeInspection */
        require $template_file;
        $content = ob_get_clean();

        $code = '<?php' . PHP_EOL . $content;

        file_put_contents($path, $code);

        return $path;
    }
}