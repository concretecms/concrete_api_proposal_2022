<?php

namespace Concrete\Proposals\Api\Attribute;

use League\Fractal\TransformerAbstract;

interface FractalTransformableInterface
{

    public function getApiDataTransformer(): TransformerAbstract;


}