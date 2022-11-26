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

        $this->dirs = $this->scandirdir($this->dirPath);
    }

    /**
     * @inheritDoc
     */
    public function getFileIterator(): FileIterator
    {
        $iterator = new FileIterator($this->files, $this->rootPath);
        return $iterator;
    }

    /**
     * @inheritDoc
     */
    public function getDirectoryIterator(): DirectoryIterator
    {
        $iterator = new DirectoryIterator($this->dirs, $this->rootPath);
        return $iterator;
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
    protected array $dirs = [];

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

    protected function scandirdir(string $dirPath): array
    {
        $a = scandir($dirPath);
        $dirs = [];

        foreach ($a as $path) {
            if ($path == '.' || $path == '..') {
                continue;
            }

            $path = $dirPath . '/' . $path;
            if (is_dir($path)) {
                $dirs[] = $path;
                if ($res = $this->scandirdir($path)) {
                    $dirs = array_merge($dirs, $res);
                }
            }
        }

        return $dirs;
    }
}
