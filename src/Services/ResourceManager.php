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
    public function getCategory(string $uri, int $pagenum = 1): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getCategory($uri, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeCategory($uri, $pagenum);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getTag(string $uri, int $pagenum = 1): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getTag($uri, $pagenum))) {
            return $page;
        }

        $page = $this->pageFactory->makeTag($uri, $pagenum);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getPost(string $uri): Page
    {
        if (CACHE_ENABLE && ($page = $this->cache->getPost($uri))) {
            return $page;
        }

        $page = $this->pageFactory->makePost($uri);

        if (CACHE_ENABLE && $page->getType() != Page::TYPE_SERVICE) {
            $this->cache->save($page);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getService404(): Page
    {
        return $this->pageFactory->make404();
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected CacheInterface $cache;
    protected PageFactoryInterface $pageFactory;
}
