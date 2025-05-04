<?php

declare(strict_types=1);

namespace Angkor\Categories\Providers;

use Angkor\Categories\Models\Category;
use Illuminate\Support\ServiceProvider;
use Angkor\Support\Traits\ConsoleTools;
use Illuminate\Database\Eloquent\Relations\Relation;
use Angkor\Categories\Console\Commands\MigrateCommand;
use Angkor\Categories\Console\Commands\PublishCommand;
use Angkor\Categories\Console\Commands\RollbackCommand;

class CategoriesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'angkor.categories');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'angkor.categories.category' => Category::class,
        ]);

        // Register console commands
        $this->commands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register paths to be published by the publish command.
        $this->publishConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'angkor/categories');
        $this->publishMigrationsFrom(realpath(__DIR__.'/../../database/migrations'), 'angkor/categories');

        ! $this->app['config']['angkor.categories.autoload_migrations'] || $this->loadMigrationsFrom(realpath(__DIR__.'/../../database/migrations'));

        // Map relations
        Relation::morphMap([
            'category' => config('angkor.categories.models.category'),
        ]);
    }
}
