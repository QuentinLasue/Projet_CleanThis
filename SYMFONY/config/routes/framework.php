<?php
// https://symfony.com/doc/current/controller/error_pages.html#testing-error-pages-during-development
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
// Pour visualiser et déboguer les page de redirection d'erreur. 
return function (RoutingConfigurator $routes): void {
    if ('dev' === $routes->env()) {
        $routes->import('@FrameworkBundle/Resources/config/routing/errors.xml')
            ->prefix('/_error');
    }
};
