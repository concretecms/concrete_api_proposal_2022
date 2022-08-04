<?php

namespace Concrete\Proposals\Api\Attribute;

use Concrete\Proposals\Api\OpenApi\SpecProperty;

interface SupportsAttributeValueFromJsonInterface
{

    /**
     * Could be a string, could be an array representation of a more complex request body object
     * @param mixed $json
     * @return mixed
     */
    public function createAttributeValueFromNormalizedJson($json);


}