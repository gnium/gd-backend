<?php

if (!function_exists('module_path')) {
    /**
     * Get the path of a module.
     */
    function module_path(string $moduleName, string $path = ''): string
    {
        $moduleBasePath = base_path("modules/{$moduleName}");
        return $path ? "{$moduleBasePath}/{$path}" : $moduleBasePath;
    }
}
