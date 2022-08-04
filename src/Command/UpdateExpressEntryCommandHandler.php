<?php

namespace Concrete\Proposals\Api\Command;

use Concrete\Core\Entity\Express\ManyToManyAssociation;
use Concrete\Core\Entity\Express\ManyToOneAssociation;
use Concrete\Core\Entity\Express\OneToManyAssociation;
use Concrete\Core\Entity\Express\OneToOneAssociation;
use Concrete\Core\Express\Association\Applier;

class UpdateExpressEntryCommandHandler
{

    use ExpressEntryCommandHandlerTrait;

    /**
     * @var Applier
     */
    protected $applier;

    public function __construct(Applier $applier)
    {
        $this->applier = $applier;
    }

    public function __invoke(UpdateExpressEntryCommand $command)
    {
        $entry = $command->getEntry();
        $map = $command->getAttributeMap();
        if ($map) {
            $this->handleAttributeMap($map, $entry);
        }

        $map = $command->getAssociationMap();
        if ($map) {
            $this->handleAssociationMap($map, $entry);
        }

        return $entry;
    }


}
