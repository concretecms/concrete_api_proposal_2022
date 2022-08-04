<?php

namespace Concrete\Proposals\Api\OpenApi;

class SpecPropertyRefItems implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $ref;

    /**
     * JsonSchemaRefContent constructor.
     * @param string $ref
     */
    public function __construct(string $ref)
    {
        $this->ref = $ref;
    }

    public function jsonSerialize()
    {
        return [
            'items' => [
                '$ref' => '#' . $this->ref
            ],
        ];
    }


}
