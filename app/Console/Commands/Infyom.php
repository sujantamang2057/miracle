<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

use function Laravel\Prompts\progress;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class Infyom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:infyom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model, repository and scaffolding (CRUD) files using laravel generator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generateName = select(
            label: 'What do you want to generate?',
            options: [
                'model',
                'scaffold',
                'repository',
            ],
        );

        echo "You've selected $generateName for generation" . EOL . EOL;

        $modelName = text(
            label: 'Enter Model name',
            required: true
        );

        echo "You've entered $modelName for model" . EOL . EOL;

        $tableName = select(
            label: 'Select Any Table',
            options: $this->__getTables(),
            required: true
        );

        echo "You've selected $tableName table" . EOL . EOL;

        $moduleName = select(
            label: 'Select Any Module',
            options: $this->__getActiveModuleNames(),
            required: true
        );

        echo "You've selected $moduleName module" . EOL . EOL;

        config([
            'laravel_generator.path' => $this->__getPaths($moduleName),
            'laravel_generator.namespace' => $this->__getModuleNamespaces($moduleName),
            'laravel_generator.prefixes.route' => $this->__getRoutePrefix($moduleName),
        ]);

        $command = $this->__prepareCommand($generateName, $modelName, $tableName);

        echo 'Started generation' . EOL . EOL . EOL;

        $progress = progress(
            label: 'Generating files',
            steps: 1,
            callback: function () use ($command) {
                Artisan::call($command);

                return 1;
            }
        );

        echo 'Ended generation' . EOL;
    }

    private function __prepareCommand($generateName, $modelName, $tableName)
    {
        return "infyom:$generateName $modelName --table=$tableName --fromTable -n --skip=requests";
    }

    private function __getActiveModuleNames()
    {
        $activeModules = Module::allEnabled();
        $modulesList = [];
        foreach ($activeModules as $module) {
            $modulesList[] = $module->getName(); // Access module name
        }

        return $modulesList;
    }

    private function __getTables()
    {
        $tables = \DB::select('SHOW TABLES');
        $keyName = 'Tables_in_' . env('DB_DATABASE');
        $tableNameArr = Arr::map($tables, function ($value, $key) use ($keyName) {
            return $value->$keyName;
        });

        return $tableNameArr;
    }

    private function __getPaths($module)
    {
        $modulePath = 'Modules/' . $module . '/';

        return [
            'migration' => database_path('migrations/'),

            'model' => base_path($modulePath . 'app/Models/'),

            'datatables' => base_path($modulePath . 'app/DataTables/'),

            'livewire_tables' => base_path($modulePath . 'app/Http/Livewire/'),

            'repository' => base_path($modulePath . 'app/Repositories/'),

            'routes' => base_path($modulePath . 'routes/web.php'),

            'api_routes' => base_path($modulePath . 'routes/api.php'),

            'request' => base_path($modulePath . 'app/Http/Requests/'),

            'api_request' => base_path($modulePath . 'app/Http/Requests/API/'),

            'controller' => base_path($modulePath . 'app/Http/Controllers/'),

            'api_controller' => base_path($modulePath . 'app/Http/Controllers/API/'),

            'api_resource' => base_path($modulePath . 'app/Http/Resources/'),

            'schema_files' => resource_path('model_schemas/'),

            'seeder' => database_path('seeders/'),

            'database_seeder' => database_path('seeders/DatabaseSeeder.php'),

            'factory' => database_path('factories/'),

            'view_provider' => base_path($modulePath . 'app/Providers/ViewServiceProvider.php'),

            'tests' => base_path($modulePath . 'Tests/'),

            'repository_test' => base_path($modulePath . 'Tests/Repositories/'),

            'api_test' => base_path($modulePath . 'Tests/APIs/'),

            'views' => base_path($modulePath . 'resources/views/'),

            'menu_file' => resource_path('views/layouts/menu.blade.php'),
        ];
    }

    private function __getModuleNamespaces($module)
    {
        $moduleNamespace = 'Modules\\' . $module . '\\';

        return [

            'model' => $moduleNamespace . 'app\Models',

            'datatables' => $moduleNamespace . 'app\DataTables',

            'livewire_tables' => $moduleNamespace . 'app\Http\Livewire',

            'repository' => $moduleNamespace . 'app\Repositories',

            'controller' => $moduleNamespace . 'app\Http\Controllers',

            'api_controller' => $moduleNamespace . 'app\Http\Controllers\API',

            'api_resource' => $moduleNamespace . 'app\Http\Resources',

            'request' => $moduleNamespace . 'app\Http\Requests',

            'api_request' => $moduleNamespace . 'app\Http\Requests\API',

            'seeder' => $moduleNamespace . 'Database\Seeders',

            'factory' => $moduleNamespace . 'Database\Factories',

            'tests' => $moduleNamespace . 'Tests',

            'repository_test' => $moduleNamespace . 'Tests\Repositories',

            'api_test' => $moduleNamespace . 'Tests\APIs',
        ];
    }

    private function __getRoutePrefix($module)
    {
        return strtolower($module);
    }
}
