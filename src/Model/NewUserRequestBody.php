<?php

namespace Concrete\Proposals\Api\Model;

/**
 * @OA\RequestBody(
 *     request="NewUser",
 *     description="User object that needs to be added to the CMS",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/NewUser"),
 * )
 */
class NewUserRequestBody
{



}
