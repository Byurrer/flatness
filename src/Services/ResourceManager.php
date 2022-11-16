<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

class ResourceManager implements ResourceManagerInterface
{
    public function __construct(
        CacheInterface $cache,
        PageFactoryInterface $pageFactory
    ) {
        $this->cache = $cache;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(int $pagenum = 1): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getIndex($pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeIndex($pagenum);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getCategory(string $path, int $pagenum = 1): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getCategory($path, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeCategory($path, $pagenum);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getTag(string $name, int $pagenum = 1): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getTag($name, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeTag($name, $pagenum);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getPost(string $name): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getPost($name))) {
            return $page;
        }

        $page = $this->pageFactory->makePost($name);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
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

    protected CacheInterface $cache;
    protected PageFactoryInterface $pageFactory;
}
