<?php

namespace Flatness;

/**
 * Реализация интерфейса кэша
 */
class Cache implements CacheInterface
{
    /**
     * @param string $dir полный путь до директории с кэшем
     * @param integer $ttl время жизни файлов кэша в млсек
     */
    public function __construct(
        private FileSystemInterface $fs,
        private string $dir,
        private int $ttl = 3600
    ) {
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function get(string $uri): mixed
    {
        $data = null;
        $path = $this->getPath($uri);
        if ($this->fs->existsFile($path) && $this->isAlive($path)) {
            $dataRaw = $this->fs->loadFile($path);
            $data = json_decode($dataRaw, true);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function save(string $uri, mixed $data): void
    {
        $path = $this->getPath($uri);

        $dir = dirname($path);
        if (!$this->fs->existsFile($dir)) {
            $res = $this->fs->mkDir($dir);
        }
        $cache = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->fs->saveFile($path, $cache);
    }

    //######################################################################

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->fs->rmDir($this->dir);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    /**
     * Получить путь до закэшированных данных
     *
     * @param string $uri
     * @return string
     */
    protected function getPath(string $uri = ''): string
    {
        $path = sprintf('%s/%s.json', $this->dir, $uri);
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
        $time = $this->fs->filemtime($path);
        return ($time && time() - $time < $this->ttl);
    }
}
