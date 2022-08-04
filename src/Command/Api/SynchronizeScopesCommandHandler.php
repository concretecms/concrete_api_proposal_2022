<?php

namespace Concrete\Proposals\Api\Command\Api;

use Concrete\Core\Database\Connection\Connection;
use Concrete\Proposals\Api\Spec\ProposedSpecController;

class SynchronizeScopesCommandHandler
{

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var ProposedSpecController
     */
    protected $proposedSpecController;

    public function __construct(Connection $db, ProposedSpecController $proposedSpecController)
    {
        $this->db = $db;
        $this->proposedSpecController = $proposedSpecController;
    }

    public function __invoke(SynchronizeScopesCommand $command)
    {
        $this->db->executeStatement('delete from OAuth2Scope');
        $spec = $this->proposedSpecController->getSpec();
        $schemes = $spec->components->securitySchemes;
        foreach ($schemes as $scheme) {
            $scopes = $scheme->flows[0]->scopes;
            foreach ($scopes as $scope => $description) {
                $this->db->insert('OAuth2Scope', ['identifier' => $scope, 'description' => $description]);
            }
        }
    }

}
