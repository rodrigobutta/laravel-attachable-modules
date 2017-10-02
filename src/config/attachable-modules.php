<?php

return [
    // path where modules are located (each module should have its own folder, with the boot provider and any files it needs to run)
    'modules_folder_path' => app_path('Modules'),

    // The boot service provider file is where a module is defined, with its namespaces, views, configs, langs, etc.
    // In order to Laravel Attachable Modules can recognize the boot provider of every module, it will look for every php file (recusive) with this logic:
    // {modules_folder_path}/{any_folder_name}/_{module_name}ServiceProvider.php
    'module_boot_provider_prefix' => '_',
    'module_boot_provider_suffix' => 'ServiceProvider',
];
