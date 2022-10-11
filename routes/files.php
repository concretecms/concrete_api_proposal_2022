<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Application $app
 * @var Concrete\Core\Routing\Router $router
 */

$router->get('/files', '\Concrete\Proposals\Api\Controller\Files::listFiles')
    ->setScopes('files:read')
;

$router->get('/files/{fID}', '\Concrete\Proposals\Api\Controller\Files::read')
    ->setScopes('files:read')
;

$router->post('/files', '\Concrete\Proposals\Api\Controller\Files::add')
    ->setScopes('files:add')
;

$router->put('/files/{fID}', '\Concrete\Proposals\Api\Controller\Files::update')
    ->setScopes('files:update')
;

$router->post('/files/{fID}/move', '\Concrete\Proposals\Api\Controller\Files::move')
    ->setScopes('files:update')
;

$router->delete('/files/{fID}', '\Concrete\Proposals\Api\Controller\Files::delete')
    ->setScopes('files:delete')
;
