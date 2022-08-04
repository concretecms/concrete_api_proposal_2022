<?php

namespace Concrete\Proposals\Api\OpenApi;

class SpecComponents implements \JsonSerializable
{

    /**
     * @var SpecModel[]
     */
    protected $models = [];

    /**
     * @var SpecRequestBody[]
     */
    protected $requestBodies = [];

    public function addModel(SpecModel $model)
    {
        $this->models[] = $model;
    }

    public function addRequestBody(SpecRequestBody $requestBody)
    {
        $this->requestBodies[] = $requestBody;
    }

    /**
     * @return SpecModel[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * @return SpecRequestBody[]
     */
    public function getRequestBodies(): array
    {
        return $this->requestBodies;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->models as $model) {
            $data['schemas'][$model->getObjectName()] = $model;
        }
        foreach ($this->requestBodies as $requestBody) {
            $data['requestBodies'][$requestBody->getModelName()] = $requestBody;
        }
        return $data;
    }
}
