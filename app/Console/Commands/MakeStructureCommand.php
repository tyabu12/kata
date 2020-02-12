<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeStructureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:structure {model} {--m|migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generate model and a base repository out of the box.';

    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $structureDir = config('const.structure_dir');
        $model = ucfirst(strval($this->argument('model')));
        $pluralModel = Str::plural($model);

        if ($this->filesystem->exists(\app_path("${structureDir}/{$pluralModel}/{$model}.php"))) {
            $this->error('The given model already exists!');
            return;
        }

        $this->createFolders($model, $structureDir);
        $this->createFile(
            $model,
            \app_path('Console/Stubs/DummyRepository.stub'),
            \app_path("{$structureDir}/{$pluralModel}/Repositories/{$model}Repository.php")
        );
        $this->createFile(
            $model,
            \app_path('Console/Stubs/DummyRepositoryInterface.stub'),
            \app_path("{$structureDir}/{$pluralModel}/Repositories/Interfaces/{$model}RepositoryInterface.php")
        );
        $this->info("File structure for {$model} created.");

        $this->call('make:model', [
            'name' => "$structureDir/$pluralModel/$model",
            '--migration' => $this->option('migration'),
        ]);
    }

    /**
     * Create all required folders
     *
     * @param string $model
     * @param string $basePath
     * @return void
     */
    protected function createFolders(string $model, string $basePath): void
    {
        $pluralModel = Str::plural($model);
        $this->filesystem->makeDirectory(\app_path("$basePath/{$pluralModel}/Requests"), 0755, true);
        $this->filesystem->makeDirectory(\app_path("$basePath/{$pluralModel}/Repositories/Interfaces"), 0755, true);
    }

    /**
     * Create source from dummy model name
     *
     * @param string $model
     * @param string $dummySrc
     * @param string $destPath
     * @return void
     */
    protected function createFile(string $model, string $dummySrc, string $destPath): void
    {
        $pluralModel = Str::plural($model);
        $modelVar = lcfirst($model);
        $pluralVar = Str::plural($modelVar);
        $dummyRepository = $this->filesystem->get($dummySrc);
        $repositoryContent = str_replace(['Dummy', 'Dummies', 'dummy', 'dummies'], [$model, $pluralModel, $modelVar, $pluralVar], $dummyRepository);
        $this->filesystem->copy($dummySrc, $destPath);
        $this->filesystem->put($destPath, $repositoryContent);
    }
}
