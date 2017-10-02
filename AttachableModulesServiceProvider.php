<?php
namespace RodrigoButta\AttachableModules;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AttachableModulesServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/attachable-modules.php' => config_path('attachable-modules.php')
		]);
	}

	public function register()
	{
		(new AttachableModulesModel(
			$this->app, new Filesystem()
		))->run();
	}

}
