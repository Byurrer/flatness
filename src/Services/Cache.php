<?php

namespace Flatness\Core\Services;

use Flatness\Core\Resources\Page;

/**
 * Реализация интерфейса кэша страниц
 */
class Cache implements CacheInterface
{
    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(int $pagenum = 1): ?Page
    {
        $page = null;
        $path = $this->getPath(Page::TYPE_INDEX, '', $pagenum);
        if (file_exists($path)) {
            $pageRaw = file_get_contents($path);
            $page = Page::fromArray(json_decode($pageRaw, true));
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getCategory(string $uri, int $pagenum = 1): ?Page
    {
        $page = null;
        $path = $this->getPath(Page::TYPE_CATEGORY, $uri, $pagenum);
        if (file_exists($path)) {
            $pageRaw = file_get_contents($path);
            $page = Page::fromArray(json_decode($pageRaw, true));
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getTag(string $uri, int $pagenum = 1): ?Page
    {
        $page = null;
        $path = $this->getPath(Page::TYPE_TAG, $uri, $pagenum);
        if (file_exists($path)) {
            $pageRaw = file_get_contents($path);
            $page = Page::fromArray(json_decode($pageRaw, true));
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function getPost(string $uri): ?Page
    {
        $page = null;
        $path = $this->getPath(Page::TYPE_POST, $uri);
        if (file_exists($path)) {
            $pageRaw = file_get_contents($path);
            $page = Page::fromArray(json_decode($pageRaw, true));
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function save(Page $page): void
    {
        $path = $this->getPath($page->getType(), $page->getUri(), $page->getPagenum());

        $dir = dirname($path);
        if (!file_exists($dir)) {
            $res = mkdir($dir, 0777, true);
        }
        $a = $page->asArray();
        $cache = json_encode($a);
        file_put_contents($path, $cache);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->rmDir($this->dir);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $dir = '';

    //######################################################################

    protected function getPath(string $type, string $uri = '', ?int $pagenum = null): string
    {
        $path = sprintf(
            '%s/%s/%s-%d.json',
            $this->dir,
            $type,
            $uri,
            ($pagenum ? $pagenum : 0)
        );

        return $path;
    }

    protected function rmDir(string $path): bool
    {
        if (is_file($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            $a = scandir($path);
            foreach ($a as $p) {
                if (($p != '.') && ($p != '..')) {
                    $this->rmDir($path . DIRECTORY_SEPARATOR . $p);
                }
            }
            return rmdir($path);
        }
        return false;
    }
}
