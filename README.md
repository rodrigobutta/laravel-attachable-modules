# Laravel Attachable Modules
Make your project scalable with isolated modules

## How it works

A project will be made of modules, for example, you may want a Products module and a Users module, in wich case your project structure will have this:
\App\Modules\Project\****
\App\Modules\User\****

Where _Project_ an _User_ are the respective folders for each module and will contain every needed file inside (no file should be spread outside this folder besides config or database matter files)

Inside every module folder, there should be a Service Provider that we will call **Boot Service Provider**. The boot service provider file is where a module is defined, with its namespaces, views, configs, langs, etc.

Laravel Attachable Modules will search for every Boot Service Provider file and use it to load every module to the project. Please refer to the config file to set this logic up (if you wont be usign the default one).

Theres a demo module inside this package ("demos") that has the needed structure and works with RodrigoButta/laravel-admin for handling the backend in a bunch of lines. But of course you can get rid of that and user the module like a boilerplate.


## Installation

composer.json:
```JSON
"require": {
  "rodrigobutta/laravel-attachable-modules": ">=1.0.0",
}
```

terminal:
```
composer update
```

config/app.php:
```php
'providers' => [
    [...]
    RodrigoButta\AttachableModules\AttachableModulesServiceProvider::class

],
```

terminal:
```
php artisan vendor:publish
```


config/attachable-modules.php

- **modules_folder_path**: path where modules are located (each module should have its own folder, with the boot provider and any files it needs to run)
- **module_boot_provider_prefix**: expected prefix for the boot service provider file
- **module_boot_provider_suffix**: expected suffix for the boot service provider file (without the .php extension)


## Adding a module

So easy!

1) Copy the module folder inside app\Modules\ (or the folder you've defined for storing modules)

2) terminal:
```
composer dump-autoload
```

3) The module is now beign called. Depending on the module architecture, you should know how to test or acces its funcions.