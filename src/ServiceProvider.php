<?php
namespace DreamFactory\Core\SqlDb;

use DreamFactory\Core\Enums\ServiceTypeGroups;
use DreamFactory\Core\Models\SystemTableModelMapper;
use DreamFactory\Core\Services\ServiceManager;
use DreamFactory\Core\Services\ServiceType;
use DreamFactory\Core\SqlDb\Models\MySqlDbConfig;
use DreamFactory\Core\SqlDb\Models\PgSqlDbConfig;
use DreamFactory\Core\SqlDb\Models\SqlDbConfig;
use DreamFactory\Core\SqlDb\Models\SqliteDbConfig;
use DreamFactory\Core\SqlDb\Services\MySqlDb;
use DreamFactory\Core\SqlDb\Services\PostgreSqlDb;
use DreamFactory\Core\SqlDb\Services\SqliteDb;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // Add our service types.
        $this->app->resolving('df.service', function (ServiceManager $df){
            $df->addType(
                new ServiceType([
                    'name'           => 'mysql',
                    'label'          => 'MySQL',
                    'description'    => 'Database service supporting MySQL connections.',
                    'group'          => ServiceTypeGroups::DATABASE,
                    'config_handler' => MySqlDbConfig::class,
                    'factory'        => function ($config){
                        return new MySqlDb($config);
                    },
                ])
            );
            $df->addType(
                new ServiceType([
                    'name'           => 'pgsql',
                    'label'          => 'PostgreSQL',
                    'description'    => 'Database service supporting PostgreSQL connections.',
                    'group'          => ServiceTypeGroups::DATABASE,
                    'config_handler' => PgSqlDbConfig::class,
                    'factory'        => function ($config){
                        return new PostgreSqlDb($config);
                    },
                ])
            );
            $df->addType(
                new ServiceType([
                    'name'           => 'sqlite',
                    'label'          => 'SQLite',
                    'description'    => 'Database service supporting SQLite connections.',
                    'group'          => ServiceTypeGroups::DATABASE,
                    'config_handler' => SqliteDbConfig::class,
                    'factory'        => function ($config){
                        return new SqliteDb($config);
                    },
                ])
            );
        });

        // Add our table model mapping
        $this->app->resolving('df.system.table_model_map', function (SystemTableModelMapper $df){
            $df->addMapping('sql_db_config', SqlDbConfig::class);
        });
    }
}
