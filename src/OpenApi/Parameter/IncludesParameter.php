<?php

namespace Concrete\Proposals\Api\OpenApi\Parameter;

use Concrete\Proposals\Api\OpenApi\SpecParameter;
use Concrete\Proposals\Api\OpenApi\SpecSchema;

class IncludesParameter implements ParameterInterface
{

    /**
     * @var string[]
     */
    protected $includes;

    public function __construct(array $includes)
    {
        $this->includes = $includes;
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'includes',
            'in' => 'query',
            'explode' => false,
            'schema' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'enum' => $this->includes,
                ],
            ],
        ];
    }

}
