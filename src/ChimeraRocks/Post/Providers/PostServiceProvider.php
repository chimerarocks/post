<?php

namespace ChimeraRocks\Post\Providers;

use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../../../resources/migrations/' => base_path('database/migrations')
			],'migrations');

		$this->loadViewsFrom(__DIR__ . '/../../../resources/views/chimerapost', 'chimerapost');

		require __DIR__ . '/../Routes.php';
	}

	/**
     * Register the service provider.
     *
     * @return void
     */
	public function register()
	{
		$this->app->bind(
			\ChimeraRocks\Category\Models\PostInterface::class,
				\ChimeraRocks\Post\Models\Post::class
		);

		$this->app->bind(
			\ChimeraRocks\Post\Repositories\PostRepositoryInterface::class,
				\ChimeraRocks\Post\Repositories\PostRepositoryEloquent::class
		);

		$this->app->register(
			\Ktquez\Tinymce\TinymceServiceProvider::class
		);
	}
}