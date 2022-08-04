<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Application $app
 * @var Concrete\Core\Routing\Router $router
 */

$router->get('/groups', '\Concrete\Proposals\Api\Controller\Groups::listGroups')
    ->setScopes('groups:read')
;

$router->get('/groups/{gID}', '\Concrete\Proposals\Api\Controller\Groups::read')
    ->setRequirement('gID', '[0-9]+')
    ->setScopes('groups:read')
;

$router->post('/groups', '\Concrete\Proposals\Api\Controller\Groups::add')
    ->setRequirement('gID', '[0-9]+')
    ->setScopes('groups:add')
;
