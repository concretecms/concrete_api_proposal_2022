<?php

namespace Concrete\Proposals\Api\OAuth;

use Concrete\Core\Entity\OAuth\Client;
use Concrete\Core\Entity\OAuth\ClientRepository as ClientEntityRepository;
use Concrete\Core\Url\Resolver\PathUrlResolver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class ClientRepository - this is a custom client repository that wraps the clientrepository entityrepository
 * found in the core. We can't just swap that class out because Doctrine ORM annotations are hard coded to use it
 * So instead we're going to create this new class which is used for all League OAuth2 server operations. It
 * wraps/decorates the old repository. Why do we need this class then? Because the existing ClientRepository
 * doesn't return a valid redirectUri - which is mandatory if we want to use the Authorization Code flow (which we
 * do for certain API operations.)
 *
 * @package Concrete\Proposals\Api\OAuth
 */
class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var PathUrlResolver
     */
    protected $urlResolver;

    public function __construct(EntityManager $entityManager, PathUrlResolver $urlResolver)
    {
        $this->entityManager = $entityManager;
        $this->urlResolver = $urlResolver;
    }

    private function getWrappedDoctrineRepository(): EntityRepository
    {
        /** @var $entityRepository ClientEntityRepository */
        $entityRepository = $this->entityManager->getRepository(Client::class);
        return $entityRepository;
    }

    public function getClientEntity($clientIdentifier)
    {
        return $this->getWrappedDoctrineRepository()->getClientEntity($clientIdentifier);
    }

    private function isClientSecretHashed(string $clientSecret)
    {
        return password_get_info($clientSecret)['algoName'] !== 'unknown';
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        /** @var ClientEntityInterface $client */
        $client = $this->findOneBy(['clientKey' => $clientIdentifier]);

        if ($client->getClientSecret() && $clientSecret) {
            if ($this->isClientSecretHashed($client->getClientSecret())) {
                return password_verify($clientSecret, $client->getClientSecret());
            } else {
                return $client->getClientSecret() === $clientSecret;
            }
        }

        return false;
    }

    public function __call($method, $arguments)
    {
        return $this->getWrappedDoctrineRepository()->$method(...$arguments);
    }


}
