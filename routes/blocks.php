<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Application $app
 * @var Concrete\Core\Routing\Router $router
 */

$router->get('/blocks/{bID}', '\Concrete\Proposals\Api\Controller\Blocks::read')
    ->setRequirement('bID', '[0-9]+')
    ->setScopes('blocks:read')
;

$router->delete('/blocks/{bID}', '\Concrete\Proposals\Api\Controller\Blocks::delete')
    ->setRequirement('bID', '[0-9]+')
    ->setScopes('blocks:delete')
;
