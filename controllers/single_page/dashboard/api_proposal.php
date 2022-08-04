<?php

namespace Concrete\Package\ConcreteApiProposal2022\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class ApiProposal extends DashboardPageController
{

    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }

}
