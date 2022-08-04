<?php

namespace Concrete\Proposals\Api\Controller;

use Concrete\Core\Page\Collection\Version\Version;
use Concrete\Core\Page\Collection\Version\VersionList;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Search\Column\Column;
use Concrete\Core\Search\Pagination\PagerPagination;
use Concrete\Proposals\Api\ApiController;
use Concrete\Proposals\Api\Fractal\Transformer\CollectionVersionTransformer;
use Concrete\Proposals\Api\Fractal\Transformer\PageTransformer;
use Concrete\Proposals\Api\Resources;
use Concrete\Proposals\Api\Traits\SetListLimitFromQueryTrait;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceAbstract;

class Versions extends ApiController
{

    use SetListLimitFromQueryTrait;

    /**
     * @OA\Get(
     *     path="/ccm/api/proposed/page_versions/{pageID}/{versionID}",
     *     tags={"page_versions"},
     *     summary="Find a page version by its ID and the ID of its page.",
     *     security={
     *         {"authorization": {"pages:versions:read"}}
     *     },
     *     @OA\Parameter(
     *         name="pageID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="versionID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PageVersion"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You do not have the proper permissions to access this resource."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page version not found"
     *     ),
     * )
     */
    public function read($pageID, $versionID)
    {
        $page = Page::getByID($pageID);
        if (!$page) {
            return $this->error(t('Page not found'), 404);
        }
        $checker = new Checker($page);
        if (!$checker->canViewPageVersions()) {
            return $this->error(t('You do not have access to read versions about this page.'), 401);
        }

        $version = Version::get($page, (int) $versionID);
        if ($version->isError() && $version->getError() === VERSION_NOT_FOUND) {
            return $this->error(t('Version not found'), 404);
        }

        return $this->transform($version, new CollectionVersionTransformer(), Resources::RESOURCE_PAGE_VERSIONS);
    }

    /**
     * @OA\Get(
     *     path="/ccm/api/proposed/page_versions/{pageID}",
     *     tags={"page_versions"},
     *     summary="Returns a list of page version objects for a given page ID, sorted by date created descending.",
     *     security={
     *         {"authorization": {"pages:versions:read"}}
     *     },
     *     @OA\Parameter(
     *         name="pageID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="The number of objects to return. Must be 100 or less. Defaults to 10.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="resultsPage",
     *         in="query",
     *         description="The page of results to retrieve. Default is 1.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PageVersion")
     *         ),
     *     ),
     * )
     */
    public function listVersions($pageID)
    {
        $page = Page::getByID($pageID);
        if (!$page) {
            return $this->error(t('Page not found'), 404);
        }
        $checker = new Checker($page);
        if (!$checker->canViewPageVersions()) {
            return $this->error(t('You do not have access to read versions about this page.'), 401);
        }
        $list = new VersionList($page);
        $this->addLimitToPaginationIfSpecified($list, $this->request);
        $resultsPage = (int) $this->request->query->get('resultsPage', 1);
        $results = $list->getPage($resultsPage);
        $resource = new Collection($results, new CollectionVersionTransformer(), Resources::RESOURCE_PAGE_VERSIONS);

        return $resource;
    }

    /**
     * @OA\Delete(
     *     path="/ccm/api/proposed/page_versions/{pageID}/{versionID}",
     *     tags={"page_versions"},
     *     summary="Delete a page version.",
     *     security={
     *         {"authorization": {"pages:versions:delete"}}
     *     },
     *     @OA\Parameter(
     *         name="pageID",
     *         in="path",
     *         description="ID of page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="versionID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DeletedResponse"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You do not have the proper permissions to delete this resource."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page version found"
     *     ),
     * )
     */
    public function delete($pageID, $versionID)
    {
        $page = Page::getByID($pageID);
        if (!$page) {
            return $this->error(t('Page not found'), 404);
        }
        $checker = new Checker($page);
        if (!$checker->canDeletePageVersions()) {
            return $this->error(t('You do not have access to delete page versions from this page.'), 401);
        }
        $version = Version::get($page, (int) $versionID);
        if ($version->isError() && $version->getError() === VERSION_NOT_FOUND) {
            return $this->error(t('Version not found'), 404);
        }

        if ($version->isApproved()) {
            return $this->error(t('You may not delete the approved version of a page.'), 401);
        }

        $version->delete();

        return $this->deleted(Resources::RESOURCE_PAGE_VERSIONS, $versionID);
    }

    /**
     * @OA\Put(
     *     path="/ccm/api/proposed/page_versions/{pageID}/{versionID}",
     *     tags={"page_versions"},
     *     summary="Update a page version",
     *     security={
     *         {"authorization": {"pages:versions:update"}}
     *     },
     *     @OA\Parameter(
     *         name="pageID",
     *         in="path",
     *         description="ID of page to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="versionID",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdatedPageVersion"),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PageVersion"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You do not have the proper permissions to update this resource."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Version not found"
     *     ),
     * )
     */
    public function update($pageID, $versionID)
    {
        $cache = $this->app->make('cache/request');
        $cache->disable();

        $page = Page::getByID($pageID);
        if (!$page) {
            return $this->error(t('Page not found'), 404);
        }
        $checker = new Checker($page);
        if (!$checker->canApprovePageVersions()) {
            return $this->error(t('You do not have access to approve page versions from this page.'), 401);
        }
        $version = Version::get($page, $versionID);
        if ($version->isError() && $version->getError() === VERSION_NOT_FOUND) {
            return $this->error(t('Version not found'), 404);
        }

        $body = json_decode($this->request->getContent(), true);
        if (isset($body['is_approved'])) {
            $approved = $body['is_approved'] ?? false;
            if ($approved) {
                $version->approve();
            } else {
                $version->deny();
            }
        }
        if (isset($body['publish_end_date'])) {
            $version->setPublishEndDate($body['publish_end_date']);
        }

        return $this->transform($version, new CollectionVersionTransformer(), Resources::RESOURCE_PAGE_VERSIONS);
    }

}
