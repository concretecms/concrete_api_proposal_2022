<?php

namespace Concrete\Proposals\Api\OAuth;

use Concrete\Core\Entity\OAuth\ScopeRepository as ScopeEntityRepository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class ScopeRepository - this is a custom scope repository that wraps the ScopeRepository entityrepository
 * found in the core. We need this because we're going to dynamically deliver Express scopes as well based
 * on objects in the system. Eventually we probably won't need this because we'll just add the scopes to the
 * database table when an admin marks an Express object as included in the API. But in this example package we don't
 * do that so let's just deliver all of them with a custom repository
 *
 * @package Concrete\Proposals\Api\OAuth
 */
class ScopeRepository implements ScopeRepositoryInterface
{

    protected $scopes;

    public function __construct(ScopeEntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    private function populateScopes()
    {
        $scopes = $this->entityRepository->findAll();

        $this->scopes = $scopes;
    }
    public function getScopeEntityByIdentifier($identifier)
    {
        if (!isset($this->scopes)) {
            $this->populateScopes();
        }
        // TODO: Implement getScopeEntityByIdentifier() method.
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // TODO: Implement finalizeScopes() method.
    }

}
