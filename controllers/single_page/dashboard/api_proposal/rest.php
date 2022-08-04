<?php

namespace Concrete\Package\ConcreteApiProposal2022\Controller\SinglePage\Dashboard\ApiProposal;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Proposals\Api\Command\Api\SynchronizeScopesCommand;

class Rest extends DashboardPageController
{

    public function synchronize_scopes()
    {
        if (!$this->token->validate('synchronize_scopes')) {
            $this->error->add($this->token->getErrorMessage());
        }
        if (!$this->error->has()) {
            $command = new SynchronizeScopesCommand();
            $this->app->executeCommand($command);
            $this->flash('success', t('Scopes synchronized.'));
            return $this->buildRedirect('/dashboard/api_proposal/rest');
        }
    }

}
