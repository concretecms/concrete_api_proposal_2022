<?php

namespace Concrete\Package\ConcreteApiProposal2022\Controller;

use Concrete\Core\Controller\Controller;
use Concrete\Core\Entity\OAuth\Client;
use Concrete\Core\View\View;
use Concrete\Package\ConcreteApiProposal2022\Controller as ApiPackageController;
use Doctrine\ORM\EntityManager;

class SwaggerUi extends Controller
{

    public function view()
    {
        $site = $this->app->make('site')->getSite();
        $em = $this->app->make(EntityManager::class);
        $client = $em->getRepository(Client::class)
            ->findOneByName(ApiPackageController::REST_API_CONNECTION_NAME);
        if ($client) {
            $this->set('clientKey', $client->getClientKey());
            $this->set('clientSecret', $client->getClientSecret());
        }
        $this->set('oauth2RedirectUrl',
            rtrim($site->getSiteCanonicalUrl(), '/')
            . ApiPackageController::SWAGGER_OAUTH2_CALLBACK
        );
    }

    public function getViewObject()
    {
        $view = new View('/swagger_ui');
        $view->setPackageHandle('concrete_api_proposal_2022');
        $view->setViewTheme(null);
        return $view;
    }
}
