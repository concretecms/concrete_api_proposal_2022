<?php

namespace Concrete\Proposals\Api\OpenApi\Parameter;

use Concrete\Proposals\Api\OpenApi\SpecParameter;
use Concrete\Proposals\Api\OpenApi\SpecSchema;

class LimitParameter extends SpecParameter
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'limit';
    }

    /**
     * @return string
     */
    public function getIn(): string
    {
        return 'query';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return t('The number of objects to return. Must be 100 or less. Defaults to 10.');
    }

    public function getSchema(): ?SpecSchema
    {
        return new SpecSchema('integer', 'int64');
    }


}
