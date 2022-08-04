<?php

namespace Concrete\Proposals\Api\Area;

use Concrete\Core\Page\Page;

/**
 * Class ApiArea. Wrapper object for working with areas without all the cruft of the legacy area class, and an easier
 * way to set the page object, etc...
 *
 * @package Concrete\Proposals\Api
 */
class ApiArea
{

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var string
     */
    protected $arHandle;

    /**
     * ApiArea constructor.
     * @param Page $page
     * @param string $arHandle
     */
    public function __construct(Page $page, string $arHandle)
    {
        $this->page = $page;
        $this->arHandle = $arHandle;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getAreaHandle(): string
    {
        return $this->arHandle;
    }

}
