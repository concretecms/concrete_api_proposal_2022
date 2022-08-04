<?php

namespace Concrete\Proposals\Api\Controller;

use Concrete\Core\System\Info;
use Concrete\Core\System\InfoTransformer;
use League\Fractal\Resource\Item;

class System
{

    /**
     * @OA\Get(
     *     path="/ccm/api/proposed/system/info",
     *     tags={"system"},
     *     security={
     *         {"clientCredentials": {"system:info:read"}}
     *     },
     *     @OA\Response(response="200", description="The info object in JSON format")
     * )
     */
    public function info()
    {
        return new Item(new Info(), new InfoTransformer());
    }

}
