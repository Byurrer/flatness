<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

class ResourceManager implements ResourceManagerInterface
{
    public function __construct(
        PageFactoryInterface $pageFactory,
        CacheInterface $cache = null
    ) {
        $this->cache = $cache;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(int $pagenum = 1): Page
    {
        if ($this->cache && ($page = $this->cache->getIndex($pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeIndex($pagenum);

        if ($this->cache && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getCategory(string $path, int $pagenum = 1): Page
    {
        if ($this->cache && ($page = $this->cache->getCategory($path, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeCategory($path, $pagenum);

        if ($this->cache && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getTag(string $name, int $pagenum = 1): Page
    {
        if ($this->cache && ($page = $this->cache->getTag($name, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeTag($name, $pagenum);

        if ($this->cache && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getPost(string $name): Page
    {
        if ($this->cache && ($page = $this->cache->getPost($name))) {
            return $page;
        }

        $page = $this->pageFactory->makePost($name);

        if ($this->cache && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getService(int $code): Page
    {
        return $this->pageFactory->makeService($code);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected PageFactoryInterface $pageFactory;
    protected ?CacheInterface $cache;
}
