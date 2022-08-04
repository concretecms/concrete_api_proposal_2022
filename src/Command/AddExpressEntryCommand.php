<?php

namespace Concrete\Proposals\Api\Command;

use Concrete\Core\Entity\Express\Entity;
use Concrete\Core\Foundation\Command\Command;
use Concrete\Proposals\Api\Association\AssociationMap;
use Concrete\Proposals\Api\Attribute\AttributeValueMap;
use Symfony\Component\HttpFoundation\Request;

class AddExpressEntryCommand extends Command
{

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var AttributeValueMap
     */
    protected $attributeMap;

    /**
     * @var AssociationMap
     */
    protected $associationMap;

    /**
     * AddExpressEntryCommand constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return AttributeValueMap
     */
    public function getAttributeMap(): ?AttributeValueMap
    {
        return $this->attributeMap;
    }

    /**
     * @param AttributeValueMap $attributeMap
     */
    public function setAttributeMap(AttributeValueMap $attributeMap): void
    {
        $this->attributeMap = $attributeMap;
    }

    /**
     * @return AssociationMap
     */
    public function getAssociationMap(): ?AssociationMap
    {
        return $this->associationMap;
    }

    /**
     * @param AssociationMap $associationMap
     */
    public function setAssociationMap(AssociationMap $associationMap): void
    {
        $this->associationMap = $associationMap;
    }


}
