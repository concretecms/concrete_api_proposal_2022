<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Application $app
 * @var Concrete\Core\Routing\Router $router
 */

$router->get('/pages', '\Concrete\Proposals\Api\Controller\Pages::listPages')
    ->setScopes('pages:read')
;

$router->get('/pages/{pageID}/children', '\Concrete\Proposals\Api\Controller\Pages::sitemapPages')
    ->setScopes('pages:read')
;

$router->post('/pages', '\Concrete\Proposals\Api\Controller\Pages::add')
    ->setScopes('pages:add')
;

$router->get('/pages/{pageID}', '\Concrete\Proposals\Api\Controller\Pages::read')
    ->setRequirement('pageID', '[0-9]+')
    ->setScopes('pages:read')
;

$router->put('/pages/{cID}', '\Concrete\Proposals\Api\Controller\Pages::update')
    ->setRequirement('pageID', '[0-9]+')
    ->setScopes('pages:update')
;

$router->delete('/pages/{pageID}', '\Concrete\Proposals\Api\Controller\Pages::delete')
    ->setRequirement('pageID', '[0-9]+')
    ->setScopes('pages:delete')
;

$router->get('/page_versions/{pageID}/{versionID}', '\Concrete\Proposals\Api\Controller\Versions::read')
    ->setRequirement('pageID', '[0-9]+')
    ->setRequirement('versionID', '[0-9]+')
    ->setScopes('pages:versions:read')
;

$router->get('/page_versions/{pageID}', '\Concrete\Proposals\Api\Controller\Versions::listVersions')
    ->setRequirement('pageID', '[0-9]+')
    ->setScopes('pages:versions:read')
;

