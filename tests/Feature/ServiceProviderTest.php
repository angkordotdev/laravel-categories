<?php

declare(strict_types=1);

namespace Angkor\Categories\Tests\Feature;

use ReflectionClass;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\ServiceProvider;
use Angkor\Categories\Models\Category;
use Angkor\Categories\Providers\CategoriesServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            CategoriesServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set up categories configuration
        $app['config']->set('angkor.categories.tables.categories', 'categories');
        $app['config']->set('angkor.categories.tables.categorizables', 'categorizables');
        $app['config']->set('angkor.categories.models.category', Category::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_extends_service_provider(): void
    {
        $reflection = new ReflectionClass(CategoriesServiceProvider::class);
        $provider = new ReflectionClass(ServiceProvider::class);

        $this->assertTrue(
            $reflection->isSubclassOf($provider),
            'CategoriesServiceProvider should extend ServiceProvider'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_registers_category_model(): void
    {
        $this->assertTrue(
            $this->app->bound('angkor.categories.category'),
            'Category model should be bound to the container'
        );

        $category = $this->app->make('angkor.categories.category');
        $this->assertInstanceOf(Category::class, $category);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_loads_correct_configuration(): void
    {
        $this->assertEquals('categories', config('angkor.categories.tables.categories'));
        $this->assertEquals('categorizables', config('angkor.categories.tables.categorizables'));
        $this->assertEquals(Category::class, config('angkor.categories.models.category'));
    }
}
