<?php

namespace Concrete\Proposals\Api\Controller;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Express\Search\ColumnSet\Column\DateLastModifiedColumn;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Search\Pagination\PagerPagination;
use Concrete\Core\User\User;
use Concrete\Proposals\Api\ApiController;
use Concrete\Proposals\Api\Command\AddExpressEntryCommand;
use Concrete\Proposals\Api\Command\ExpressEntryCommandFactory;
use Concrete\Proposals\Api\Fractal\Transformer\ExpressEntryTransformer;
use Concrete\Proposals\Api\Fractal\Transformer\UserTransformer;
use Concrete\Proposals\Api\Resources;
use Concrete\Proposals\Api\Traits\SetListLimitFromQueryTrait;
use Concrete\Proposals\Api\Traits\SupportsCursorTrait;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\JsonResponse;

class Express extends ApiController implements ApplicationAwareInterface
{

    use ApplicationAwareTrait;
    use SupportsCursorTrait;
    use SetListLimitFromQueryTrait;

    public function listItems(string $objectHandle)
    {
        /**
         * @var $express ObjectManager
         */
        $express = $this->app->make('express');
        $object = $express->getObjectByHandle($objectHandle);
        if (!$object) {
            return $this->error(t('Object not found.', 404));
        }
        $list = $express->getList($objectHandle, true);
        $dateModifiedColumn = new DateLastModifiedColumn();
        $dateModifiedColumn->setColumnSortDirection('desc');
        $this->setupSortAndCursor($this->request, $list, $dateModifiedColumn, function($currentCursor) use ($express) {
            $entry = $express->getEntry($currentCursor);
            return $entry;
        });

        $list->setPermissionsChecker(
            function ($entry) {
                return true;
            }
        );

        $pagination = new PagerPagination($list);
        $this->addLimitToPaginationIfSpecified($pagination, $this->request);

        $results = $pagination->getCurrentPageResults();
        $resource = new Collection($results, new ExpressEntryTransformer($object), $object->getPluralHandle());
        $this->addCursorToResource($results, $this->request, 'getID', $resource);

        return $resource;
    }

    protected function getEntry(string $objectHandle, $entryID)
    {
        $entryID = (int) $entryID;
        $express = $this->app->make('express');
        $object = $express->getObjectByHandle($objectHandle);
        if (!$object) {
            return $this->error(t('Object not found.', 404));
        }
        $entry = $express->getEntry($entryID);
        if (!$entry || !$entry->is($objectHandle)) {
            return $this->error(t('Invalid entry ID.', 404));
        }

        return [$object, $entry];
    }

    public function read(string $objectHandle, $entryID)
    {
        $response = $this->getEntry($objectHandle, $entryID);
        if ($response instanceof JsonResponse) {
            return $response;
        } else {
            list($object, $entry) = $response;
        }
        $permissions = new Checker($entry);
        if (!$permissions->canViewExpressEntry()) {
            return $this->error(t('You do not have access to view this entry.'), 401);
        }

        return $this->transform($entry, new ExpressEntryTransformer($object), $object->getPluralHandle());
    }

    public function update(string $objectHandle, $entryID)
    {
        $response = $this->getEntry($objectHandle, $entryID);
        if ($response instanceof JsonResponse) {
            return $response;
        } else {
            list($object, $entry) = $response;
        }
        $permissions = new Checker($entry);
        if (!$permissions->canEditExpressEntry()) {
            return $this->error(t('You do not have access to update this entry.'), 401);
        }

        $factory = $this->app->make(ExpressEntryCommandFactory::class);
        $command = $factory->createUpdateEntryCommand($object, $entry, $this->request);
        $entry = $this->app->executeCommand($command);

        return $this->transform($entry, new ExpressEntryTransformer($object), $object->getPluralHandle());
    }


    public function add(string $objectHandle)
    {
        $express = $this->app->make('express');
        $object = $express->getObjectByHandle($objectHandle);
        if (!$object) {
            return $this->error(t('Object not found.', 404));
        }
        $permissions = new Checker($object);
        if (!$permissions->canAddExpressEntries()) {
            return $this->error(t('You do not have access to add %s entries.', $object->getName()), 401);
        }

        $factory = $this->app->make(ExpressEntryCommandFactory::class);
        $command = $factory->createAddEntryCommand($object, $this->request);
        $entry = $this->app->executeCommand($command);

        return new Item($entry, new ExpressEntryTransformer($object), $object->getPluralHandle());
    }

    public function delete(string $objectHandle, $entryID)
    {
        $response = $this->getEntry($objectHandle, $entryID);
        if ($response instanceof JsonResponse) {
            return $response;
        } else {
            list($object, $entry) = $response;
        }
        $permissions = new Checker($entry);
        if (!$permissions->canDeleteExpressEntry()) {
            return $this->error(t('You do not have access to delete this entry.'), 401);
        }

        $express = $this->app->make('express');
        $express->deleteEntry($entry);

        return $this->deleted($object->getPluralHandle(), $entryID);
    }






}
