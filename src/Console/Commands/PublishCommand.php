<?php

declare(strict_types=1);

namespace Angkor\Categories\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'angkor:publish:categories')]
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'angkor:publish:categories {--f|force : Overwrite any existing files.} {--r|resource=* : Specify which resources to publish.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Angkor Categories Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        collect($this->option('resource') ?: ['config', 'migrations'])->each(function ($resource) {
            $this->call('vendor:publish', ['--tag' => "angkor/categories::{$resource}", '--force' => $this->option('force')]);
        });

        $this->line('');
    }
}
