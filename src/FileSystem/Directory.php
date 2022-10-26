<?php

namespace Flatness\Core\FileSystem;

/**
 * Реализация директории
 */
class Directory implements DirectoryInterface
{
    /**
     * @param string $dirPath путь до директории
     * @param string|null $rootPath путь до корня файлов
     */
    public function __construct(string $dirPath, string $rootPath = null)
    {
        $this->dirPath = $dirPath;
        $this->rootPath = ($rootPath ? $rootPath : $dirPath);

        $this->files = $this->scandir($this->dirPath);
        usort(
            $this->files,
            function (string $a, string $b) {
                $a = explode('_', basename($a), 2)[0];
                $b = explode('_', basename($b), 2)[0];
                if ($a == $b) {
                    return 0;
                }
                return ($a > $b) ? -1 : 1;
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function getFileIncr(): ?FileInterface
    {
        if ($this->offset < count($this->files)) {
            $file = $this->files[$this->offset++];
            if (file_exists($file)) {
                return new File($file, $this->rootPath);
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): int
    {
        return count($this->files);
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): FileInterface
    {
        $path = $this->dirPath . '/index.md';
        $file = new File($path, $this->dirPath);
        return $file;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        if ($this->dirPath == $this->rootPath) {
            return '/';
        }

        return basename($this->dirPath);
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $dirPath = '';
    protected string $rootPath = '';
    protected array $files = [];
    protected int $offset = 0;

    //######################################################################

    protected function scandir(string $dirPath): array
    {
        $a = scandir($dirPath);
        $files = [];

        foreach ($a as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $dirPath . '/' . $file;
            if (is_dir($path)) {
                if ($res = $this->scandir($path)) {
                    $files = array_merge($files, $res);
                }
            } elseif ($file != 'index.md') {
                $files[] = $path;
            }
        }

        return $files;
    }
}
