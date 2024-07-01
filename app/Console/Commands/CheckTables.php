<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsia:check_tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if tables defined in models exist in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelPath = app_path('Models'); // Sesuaikan path jika model berada di folder yang berbeda
        $modelFiles = \Illuminate\Support\Facades\File::allFiles($modelPath);

        $tables = [];

        foreach ($modelFiles as $file) {
            $modelName = 'App\\Models\\' . $file->getFilenameWithoutExtension();
            if (class_exists($modelName)) {
                $model = new $modelName();
                if (property_exists($model, 'table')) {
                    $tables[] = $model->getTable();
                }
            }
        }

        $databaseName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $existingTables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');

        $existingTableNames = array_map(function ($table) use ($databaseName) {
            return $table->{"Tables_in_$databaseName"};
        }, $existingTables);

        $tablesExist = [];
        $tablesNotExist = [];

        foreach ($tables as $table) {
            if (in_array($table, $existingTableNames)) {
                $tablesExist[] = $table;
            } else {
                $tablesNotExist[] = $table;
            }
        }

        if (!empty($tablesExist)) {
            $this->info("Tables that exist:");
            foreach ($tablesExist as $table) {
                $this->info("- {$table}");
            }
        } else {
            $this->info("No tables exist.");
        }

        if (!empty($tablesNotExist)) {
            $this->warn("Tables that do not exist:");
            foreach ($tablesNotExist as $table) {
                $this->warn("- {$table}");
            }
        } else {
            $this->info("All tables exist.");
        }

        return 0;
    }
}
