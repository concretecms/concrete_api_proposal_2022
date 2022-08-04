<?php

namespace Concrete\Proposals\Api\Response;

/**
 * @OA\Schema(
 *     title="Deletion Successful",
 * )
 */
class DeletedAreaBlockResponse extends DeletedResponse
{

    /**
     * @OA\Property(title="Version", ref="#/components/schemas/PageVersion")
     *
     * @var string
     */
    private $version;


}