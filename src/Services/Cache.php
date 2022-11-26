<?php

namespace Flatness\Core\Services;

/**
 * Реализация интерфейса кэша страниц
 */
class Cache implements CacheInterface
{
    /**
     * @param string $dir полный путь до директории с кэшем
     * @param integer $ttl время жизни файлов кэша в млсек
     */
    public function __construct(string $dir, int $ttl = 3600)
    {
        $this->dir = $dir;
        $this->ttl = $ttl;
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function getPage(string $uri): ?string
    {
        $page = null;
        $path = $this->getPagePath($uri);
        if (file_exists($path) && $this->isAlive($path)) {
            $page = file_get_contents($path);
        }

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function savePage(string $uri, string $page): void
    {
        $path = $this->getPagePath($uri);

        $dir = dirname($path);
        if (!file_exists($dir)) {
            $res = mkdir($dir, 0777, true);
        }
        file_put_contents($path, $page);
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function getData(string $uri): ?array
    {
        $data = null;
        $path = $this->getDataPath($uri);
        if (file_exists($path) && $this->isAlive($path)) {
            $dataRaw = file_get_contents($path);
            $data = json_decode($dataRaw, true);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function saveData(string $uri, array $data): void
    {
        $path = $this->getDataPath($uri);

        $dir = dirname($path);
        if (!file_exists($dir)) {
            $res = mkdir($dir, 0777, true);
        }
        $cache = json_encode($data);
        file_put_contents($path, $cache);
    }

    //######################################################################

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
    protected int $ttl = 0;

    //######################################################################

    /**
     * Получить путь до закэшированной страницы
     *
     * @param string $uri
     * @return string
     */
    protected function getPagePath(string $uri = ''): string
    {
        $path = sprintf(
            '%s/pages/%s.html',
            $this->dir,
            $uri,
        );

        return $path;
    }

    /**
     * Получить путь до закэшированных данных
     *
     * @param string $uri
     * @return string
     */
    protected function getDataPath(string $uri = ''): string
    {
        $path = sprintf(
            '%s/data/%s.json',
            $this->dir,
            $uri,
        );

        return $path;
    }

    /**
     * Жив ли файл кэша
     *
     * @param string $path
     * @return boolean
     */
    protected function isAlive(string $path): bool
    {
        $time = filemtime($path);
        return ($time && time() - $time < $this->ttl);
    }

    /**
     * Рекурсивное удаление директории кэша
     *
     * @param string $path
     * @return boolean
     */
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
