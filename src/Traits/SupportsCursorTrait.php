<?php

namespace Concrete\Proposals\Api\Traits;

use Concrete\Core\Search\Column\ColumnInterface;
use Concrete\Core\Search\Column\PagerColumnInterface;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Core\Utility\Service\Validation\Numbers;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\ResourceAbstract;
use Symfony\Component\HttpFoundation\Request;

trait SupportsCursorTrait
{

    public function getCurrentCursorFromRequest(Request $request)
    {
        $currentCursor = (int)$this->request->query->get('after', null);
        if ($currentCursor) {
            $numbers = new Numbers();
            if ($numbers->integer($currentCursor)) {
                return $currentCursor;
            }
        }
        return null;
    }

    public function addCursorToResource(
        array $results,
        Request $request,
        string $getNewCursorMethod,
        ResourceAbstract $resource,
        $previousCursor = null
    ) {
        if (count($results) > 0) {
            $newCursor = collect($results)->last()->$getNewCursorMethod();
        } else {
            $newCursor = null;
        }

        $cursor = new Cursor(
            $this->getCurrentCursorFromRequest($request), $previousCursor, $newCursor, count($results)
        );
        $resource->setCursor($cursor);
        return $resource;
    }

    public function setupSortAndCursor(
        Request $request,
        ItemList $list,
        PagerColumnInterface $column,
        callable $getCursorObjectFunction
    ) {
        $currentCursor = $this->getCurrentCursorFromRequest($request);
        $list->sortBySearchColumn($column);
        if ($currentCursor > 0) {
            $object = $getCursorObjectFunction($currentCursor);
            if ($object) {
                $column->filterListAtOffset($list, $object);
            }
        }
    }
}
