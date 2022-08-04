<?php

namespace Concrete\Proposals\Api\Model;

/**
 * @OA\Schema(title="ChangeUserPassword model")
 */
class ChangeUserPassword
{
    
    /**
     * @OA\Property(type="string", title="New password")
     */
    private $password;
    
}
