<?php

/**
 * Agregador de Rotas Modular
 * Este arquivo percorre todos os módulos em app/Modules e carrega seus arquivos routes.php
 */

$modulesPath = dirname(__DIR__) . '/app/Modules';
$modules = array_diff(scandir($modulesPath), ['.', '..']);

$allRoutes = [
    "GET" => [],
    "POST" => []
];

foreach ($modules as $module) {
    $routeFile = "$modulesPath/$module/routes.php";
    
    if (file_exists($routeFile)) {
        $moduleRoutes = include $routeFile;
        
        if (isset($moduleRoutes['GET'])) {
            $allRoutes['GET'] = array_merge($allRoutes['GET'], $moduleRoutes['GET']);
        }
        
        if (isset($moduleRoutes['POST'])) {
            $allRoutes['POST'] = array_merge($allRoutes['POST'], $moduleRoutes['POST']);
        }
    }
}

return $allRoutes;
