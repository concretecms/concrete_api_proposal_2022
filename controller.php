<?php

namespace Concrete\Package\ConcreteApiProposal2022;

use Concrete\Core\Api\OAuth\Client\ClientFactory;
use Concrete\Core\Entity\OAuth\Client;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Foundation\Psr4ClassLoader;
use Concrete\Core\Package\Package;
use Concrete\Proposals\Api\ApiRouteList;
use Concrete\Proposals\Api\Command\Api\SynchronizeScopesCommand;
use Concrete\Proposals\Api\Controller\Authorize;
use Concrete\Proposals\Api\OAuth\ClientRepository;
use Concrete\Proposals\Api\Spec\ProposedSpecController;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class Controller extends Package
{
    protected $appVersionRequired = '9.0.2';
    protected $pkgVersion = '0.8.1';
    protected $pkgHandle = 'concrete_api_proposal_2022';

    const REST_API_CONNECTION_NAME = 'Concrete CMS API Proposal';
    const SWAGGER_OAUTH2_CALLBACK = '/packages/concrete_api_proposal_2022/swagger/oauth2-redirect.html';
    
    public function getPackageName()
    {
        return t('Concrete CMS Api Proposal 2022');
    }

    public function getPackageDescription()
    {
        return t('Implements a proposed REST and GraphQL API for Concrete CMS.');
    }

    /*
     * Note: We're NOT going to use this feature. Instead we're going to manually register this using the Psr4AutoLoader
     * Why? Because if we do this, it turns that namespace into a Doctrine entity location. Normally that would be fine
     * (even though it's not ideal) but we use custom annotations in this location for OpenAPI and Doctrine complains
     * about those.
    protected $pkgAutoloaderRegistries = array(
        'src' => '\Concrete\Proposals\Api',
    );
    */

    public function on_start()
    {
        if (file_exists($this->getPackagePath() . "/vendor")) {
            require_once $this->getPackagePath() . "/vendor/autoload.php";
        }

        $loader = new Psr4ClassLoader();
        $loader->addPrefix('Concrete\Proposals\Api', __DIR__ . '/src');
        $loader->register();

        $config = $this->app->make("config");
        $router = $this->app->make('router');
        if ($config->get('concrete.api.enabled')) {
            $list = new ApiRouteList();
            $list->loadRoutes($router);
        }

        // Provide the generated API route
        $router->get('/ccm/api/proposed/spec', [ProposedSpecController::class, 'generate']);

        // Swagger UI
        $router->get('/ccm/proposals/swagger_ui', 'Concrete\Package\ConcreteApiProposal2022\Controller\SwaggerUi::view');

        // We also need to update our Client Repository – the core version always assumes that the client secret
        // is hashed. But for convenience in this particular setup I do NOT want to hash it because I want to
        // allow it to be passed to our Swagger UI (Note: not for use with production)
        $this->app->bind(ClientRepositoryInterface::class, function() {
            return $this->app->make(ClientRepository::class);
        });

    }

    /**
     * Creates a REST API connection with the proper redirect Uri for our custom swagger interface
     * Names it properly so that the client id and secret can pass directly into our Swagger UI
     */
    protected function installApiConnection()
    {
        $em = $this->app->make(EntityManager::class);
        $client = $em->getRepository(Client::class)
            ->findOneByName(self::REST_API_CONNECTION_NAME);
        if (!$client) {
            $factory = $this->app->make(ClientFactory::class);
            $credentials = $factory->generateCredentials();

            // Create a new client while hashing the new secret
            $site = $this->app->make('site')->getSite();
            $client = $factory->createClient(
                self::REST_API_CONNECTION_NAME,
                rtrim($site->getSiteCanonicalURL(), '/') . self::SWAGGER_OAUTH2_CALLBACK,
                [],
                $credentials->getKey(),
                $credentials->getSecret()
            );
            $em->persist($client);
            $em->flush();
        }
    }

    /**
     * Installs the proposed new scopes for the API proposal.
     */
    protected function synchronizeScopes()
    {
        $command = new SynchronizeScopesCommand();
        $this->app->executeCommand($command);
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->synchronizeScopes();
        $this->installContentFile("install.xml");
    }

    public function validate_install()
    {
        $errors = new ErrorList();
        $config = $this->app->make('config');
        $defaultSite = $this->app->make('site')->getSite();
        if (!$defaultSite->getSiteCanonicalURL()) {
            $errors->add(t('Your default site must define a canonical URL to enable the API proposal.'));
        }

        if ($config->get('concrete.api.enabled') != true) {
            $errors->add(t('You must enable the Concrete CMS Rest API to install this package.'));
        }

        return $errors;
    }

    public function uninstall()
    {
        $em = $this->app->make(EntityManager::class);
        $client = $em->getRepository(Client::class)
            ->findOneByName(self::REST_API_CONNECTION_NAME);
        if ($client) {
            $query = $em->createQuery(
                'delete from \Concrete\Core\Entity\OAuth\AccessToken token where token.client = :client'
            );
            $query->setParameter('client', $client->getIdentifier());
            $query->execute();

            $query = $em->createQuery(
                'delete from \Concrete\Core\Entity\OAuth\Client c where c = :client'
            );
            $query->setParameter('client', $client->getIdentifier());
            $query->execute();

            $em->remove($client);
            $em->flush();
        }
        return parent::uninstall();
    }

    public function install()
    {
        parent::install();
        $this->on_start();
        $this->installApiConnection();
        $this->synchronizeScopes();
        $this->installContentFile("install.xml");
    }


}
