<?php

namespace Flatness\Core\Services;

use Flatness\Core\FileSystem\File;
use Flatness\Core\FileSystem\Directory;
use Flatness\Core\FileSystem\FileInterface;
use Flatness\Core\FileSystem\DirectoryInterface;

/**
 * Реализация файловой системы контента
 */
class Content implements ContentInterface
{
    /**
     * @param string $dir корень файлов контента
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    /**
     * @inheritDoc
     */
    public function getFile(string $uri): ?FileInterface
    {
        $path = $this->searchFile($this->dir, $uri);
        if ($path && file_exists($path) && !is_dir($path)) {
            $file = new File($path, $this->dir);
            return $file;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDirectory(string $uri): ?DirectoryInterface
    {
        $path = ($uri && $uri != '/' ? $this->dir . '/' . $uri : $this->dir);
        if (file_exists($path) && is_dir($path)) {
            $file = new Directory($path, $this->dir);
            return $file;
        }

        return null;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $dir = '';

    //######################################################################

    /**
     * Рекурсивный поиск первого подходящего файла
     *
     * @param string $dir
     * @param string $uri
     * @return string|null
     */
    protected function searchFile(string $dir, string $uri): ?string
    {
        $a = scandir($dir);

        foreach ($a as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                if ($res = $this->searchFile($path, $uri)) {
                    return $res;
                }
            }

            if (stripos($file, $uri) === 0) {
                return $path;
            }
        }

        return null;
    }
}
