<?php

namespace Concrete\Proposals\Api\OpenApi;

class SpecResponseList implements \JsonSerializable
{
    /**
     * @var SpecResponse[]
     */
    protected $responses = [];

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->responses as $response) {
            $code = $response->getCode();
            $data[$code] = $response->jsonSerialize();
        }
        return $data;
    }

    public function addResponse(SpecResponse $response)
    {
        $this->responses[] = $response;
    }

}
