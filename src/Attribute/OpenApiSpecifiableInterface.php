<?php

namespace Concrete\Proposals\Api\Attribute;

use Concrete\Proposals\Api\OpenApi\SpecProperty;

interface OpenApiSpecifiableInterface
{

    public function getOpenApiSpecProperty(): SpecProperty;


}