<?php namespace Ryanwhetstone\Combine;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CombineServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ryanwhetstone/combine');
	}

	public function register()
	{
		$this->registerCombine();
		$this->registerCombinePost();
		$this->registerCombinePage();
		$this->registerCombineCategory();
		$this->registerCombineComment();
		$this->registerCombineTaxonomy();


    // Shortcut so developers don't need to add an Alias in app/config/app.php
		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('Combine', 			'Ryanwhetstone\Combine\Facades\Combine');
			$loader->alias('CombinePost', 	'Ryanwhetstone\Combine\Facades\CombinePost');
			$loader->alias('CombinePage', 	'Ryanwhetstone\Combine\Facades\CombinePage');
			$loader->alias('CombineCategory', 	'Ryanwhetstone\Combine\Facades\CombineCategory');
			$loader->alias('CombineComment', 	'Ryanwhetstone\Combine\Facades\CombineComment');
			$loader->alias('CombineTaxonomy', 	'Ryanwhetstone\Combine\Facades\CombineTaxonomy');
		});		
	}

	protected function registerCombine($value='')
	{
		$this->app['Combine'] = $this->app->share(function($app)
		{
			return new Combine;
		});
	}
	
	protected function registerCombinePost($value='')
	{
		$this->app['CombinePost'] = $this->app->share(function($app)
		{
			return new Models\CombinePost;
		});
	}

	protected function registerCombinePage($value='')
	{
		$this->app['CombinePage'] = $this->app->share(function($app)
		{
			return new Models\CombinePage;
		});
	}

	protected function registerCombineCategory($value='')
	{
		$this->app['CombineCategory'] = $this->app->share(function($app)
		{
			return new Models\CombineCategory;
		});
	}

	protected function registerCombineComment($value='')
	{
		$this->app['CombineComment'] = $this->app->share(function($app)
		{
			return new Models\CombineComment;
		});
	}

	protected function registerCombineTaxonomy($value='')
	{
		$this->app['CombineTaxonomy'] = $this->app->share(function($app)
		{
			return new Models\CombineTaxonomy;
		});
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Combine', 'CombinePost', 'CombinePage', 'CombineCategory', 'CombineComment', 'CombineTaxonomy');
	}

}
