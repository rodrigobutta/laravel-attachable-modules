# Laravel Attachable Modules
Make your project scalable with isolated modules

## Installation

composer.json:
```JSON
"require": {
  "rodrigobutta/laravel-attachable-modules": ">=1.0.0",
}
```

terminal:
```
$ composer update
```

config/app.php:
```php
'providers' => [

    'RodrigoButta\AttachableModules\AttachableModulesServiceProvider',
],
```

terminal:
```
$ php artisan vendor:publish
```

Config your paths and stuff in config/attachable-modules.php
