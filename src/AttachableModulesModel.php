<?php
namespace RodrigoButta\AttachableModules;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AttachableModulesModel {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Repository
	 */
	protected $config;

	/**
	 * @var array
	 */
	protected $providers = [];

	/**
	 * @var Filesystem
	 */
	protected $file;

	/**
	 * @var string
	 */
	protected $appNamespace;

	/**
	 * @var string
	 */
	protected $providersFolder;

	/**
	 * @param Application $app
	 * @param Filesystem  $file
	 */
	public function __construct(Application $app, Filesystem $file)
	{
		$this->app = $app;
		$this->config = $app['config'];
		$this->file = $file;
	}

	/**
	 * Register service providers inside Providers folder
	 *
	 * @return void
	 */
	public function run()
	{
		$this->getProviders();

		$this->registerProviders();
	}

	/**
	 * Get list of service providers inside Provider folder
	 *
	 * @return array
	 */
	protected function getProviders()
	{
		if (!empty($folder = $this->config->get('attachable-modules.modules_folder_path'))) {

			// $files = $this->file->files($folder);
			$files = $this->file->allFiles($folder);

			foreach ($files as $file) {
				$fileInfo = pathinfo($file);

				if ($fileInfo['extension'] == 'php' && $fileInfo['filename'] == 'CampaignServiceProvider') {
					$this->addProvider($fileInfo);
				}
			}

		}

		return $this->providers;
	}

	/**
	 * Check for providers existance and add them to provider container
	 *
	 * @param $filename
	 */
	protected function addProvider($fileInfo)
	{
		$className = $this->guessClassName($fileInfo);

		if (class_exists($className)) {
			$this->providers[] = $className;
		}
	}

	/**
	 * Guess provider class name
	 *
	 * @param $filename
	 * @return string
	 */
	protected function guessClassName($fileInfo)
	{
		$path = $fileInfo["dirname"];
		$path = str_replace('/','\\', $path); // puede ser necesario para paths linux
		$segments = explode("\\",$path);
		$parentFolder = end($segments);

		return $this->getAppNamespace() . '\\' . $this->getModulesFolderName() . '\\' . $parentFolder . '\\' . $fileInfo['filename'];
	}

	/**
	 * Get the providers folder name
	 *
	 * @return mixed
	 */
	protected function getModulesFolderName()
	{
		if (!is_null($this->providersFolder)) {
			return $this->providersFolder;
		}

		$this->providersFolder = str_replace(
			'/',
			'\\',
			substr(
				$this->config->get('attachable-modules.modules_folder_path'),
				strlen(app_path()) + 1
			)
		);

		return $this->providersFolder;
	}


	/**
	 * Register providers to laravel's application container
	 *
	 * @param $providers
	 */
	protected function registerProviders()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);

		}
	}

	/**
	 * Get the application namespace from the Composer file.
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	protected function getAppNamespace()
	{
		if (!is_null($this->appNamespace)) {
			return $this->appNamespace;
		}

		$composer = json_decode(file_get_contents(base_path() . '/composer.json'), true);

		foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
			foreach ((array) $path as $pathChoice) {
				if (realpath(app_path()) == realpath(base_path() . '/' . $pathChoice)) {
					return $this->appNamespace = rtrim($namespace, '\\');
				}
			}
		}

		throw new \RuntimeException("Unable to detect application namespace.");
	}

}
