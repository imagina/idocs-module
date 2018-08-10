<?php

namespace Modules\Idocs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Idocs\Entities\Category;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Repositories\Cache\CacheCategoryDecorator;
use Modules\Idocs\Repositories\Cache\CacheDocDecorator;
use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Idocs\Repositories\Eloquent\EloquentCategoryRepository;
use Modules\Idocs\Repositories\Eloquent\EloquentDocRepository;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Traits\CanPublishConfiguration;

class IdocsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    public function boot()
    {
        $this->publishConfig('idocs', 'config');
        //$this->publishConfig('idocs', 'settings');
        $this->publishConfig('idocs', 'permissions');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(DocRepository::class, function () {
            $repository = new EloquentDocRepository(new Doc());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheDocDecorator($repository);
        });

        $this->app->bind(CategoryRepository::class, function () {
            $repository = new EloquentCategoryRepository(new Category());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheCategoryDecorator($repository);
        });

    }
}
