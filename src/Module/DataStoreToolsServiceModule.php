<?php
declare(strict_types=1);

namespace KnotPhp\DataStoreTools\Module;

use Throwable;

use Stk2k\EventStream\Event;

use KnotLib\DataStoreService\DI;
use KnotLib\DataStore\Constants\EventStream\Events;
use KnotLib\DataStore\Storage\Database\Database;
use KnotLib\DataStoreService\util\DataStoreComponentTrait;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\AbstractModule;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\Module\ModuleInterface;
use KnotPhp\Module\KnotDataStoreService\KnotDataStoreServiceModule;

final class DataStoreToolsServiceModule extends AbstractModule implements ModuleInterface
{
    use DataStoreComponentTrait;

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponentTypes() : array
    {
        return [
            ComponentTypes::EVENTSTREAM,
            ComponentTypes::DI,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function declareComponentType(): string
    {
        return ComponentTypes::SERVICE;
    }

    /**
     * Declare dependent on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [
            KnotDataStoreServiceModule::class,
        ];
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws  ModuleInstallationException
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $c = $app->di();

            $logger = $app->logger();

            //====================================
            // ComponentTypes
            //====================================

            // components.database factory
            $c->extend(DI::URI_COMPONENT_DATABASE, function($component) use($logger){
                /** @var Database $component */
                $component->getEventChannel()->listen(Events::STORAGE_DB_QUERY_ANY,
                    function(Event $e) use($logger)
                    {
                        $data = $e->getPayload();

                        $last_sql = $data['last_sql'] ?? '';
                        $row_count = $data['row_count'] ?? 0;
                        $elapsed_time = $data['elapsed_time'] ?? 0;

                        $logger->debug("SQL:$last_sql");
                        $logger->debug("row_count: $row_count");
                        $logger->debug("elapsed_time: $elapsed_time");
                    }
                );
                return $component;
            });

            // components.connection.default factory
            $c->extend('component://connection:default', function($component) use($logger){
                /** @var Database $component */
                $component->getEventChannel()->listen(Events::STORAGE_DB_CONNECTION_AFTER_OPEN,
                    function(Event $e) use($logger)
                    {
                        $data = $e->getPayload();

                        $dsn = $data['DSN'] ?? '';
                        $user = $data['user'] ?? '';
                        $password = $data['password'] ?? '';

                        $logger->debug("CONNECTION_OPENED:");
                        $logger->debug("DSN: $dsn");
                        $logger->debug("user: $user");
                        $logger->debug("password: $password");
                    }
                )->listen(Events::STORAGE_DB_CONNECTION_CLOSED,
                    function() use($logger)
                    {
                        $logger->debug("CONNECTION_CLOSED.");
                    }
                );
                return $component;
            });


        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }

}