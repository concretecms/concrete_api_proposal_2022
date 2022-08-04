<?php

namespace Concrete\Proposals\Api;

use Concrete\Core\Http\Middleware\ApiLoggerMiddleware;
use Concrete\Proposals\Api\Http\Middleware\FractalNegotiatorMiddleware;
use Concrete\Core\Http\Middleware\OAuthAuthenticationMiddleware;
use Concrete\Core\Http\Middleware\OAuthErrorMiddleware;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class ApiRouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        // Boilerplate code copied from the core ApiRouteList class
        $api = $router->buildGroup()
            ->setPrefix('/ccm/api/proposed')
            ->addMiddleware(OAuthErrorMiddleware::class)
            ->addMiddleware(OAuthAuthenticationMiddleware::class)
            ->addMiddleware(FractalNegotiatorMiddleware::class);

        // The ApiLoggerMiddleware needs to have high priority than the OAuthAuthenticationMiddleware
        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
        if ($app->make('config')->get('concrete.log.api')) {
            $api->addMiddleware(ApiLoggerMiddleware::class, 9);
        }

        $api->routes('system.php', 'concrete_api_proposal_2022');
        $api->routes('site.php', 'concrete_api_proposal_2022');
        $api->routes('account.php', 'concrete_api_proposal_2022');
        $api->routes('files.php', 'concrete_api_proposal_2022');
        $api->routes('users.php', 'concrete_api_proposal_2022');
        $api->routes('groups.php', 'concrete_api_proposal_2022');
        $api->routes('pages.php', 'concrete_api_proposal_2022');
        $api->routes('areas.php', 'concrete_api_proposal_2022');
        $api->routes('versions.php', 'concrete_api_proposal_2022');
        $api->routes('blocks.php', 'concrete_api_proposal_2022');
        $api->routes('express.php', 'concrete_api_proposal_2022');
    }
}
