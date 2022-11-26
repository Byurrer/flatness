<?php

namespace Flatness\Core\FileSystem;

/**
 * Реализация интерфейса файла
 */
class File implements FileInterface
{
    /**
     * @param string $filePath путь до файла
     * @param string $rootPath путь до корня файлов
     */
    public function __construct(string $filePath, string $rootPath)
    {
        $this->filePath = $filePath;
        $this->rootPath = $rootPath;
    }

    /**
     * @inheritDoc
     */
    public function getParents(): array
    {
        $path = str_replace(
            rtrim($this->rootPath, '/') . '/',
            '',
            rtrim(dirname($this->filePath))
        );
        if (!$path) {
            return [];
        }
        $a = explode('/', $path);
        return $a;
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        $content = file_get_contents($this->filePath);
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        $a = pathinfo($this->filePath);
        return strval($a['filename']);
    }

    /**
     * @inheritDoc
     */
    public function getExt(): string
    {
        $a = pathinfo($this->filePath);
        return strval($a['extension']);
    }

    /**
     * @inheritDoc
     */
    public function getRelPath(): string
    {
        $path = str_replace($this->rootPath, '', $this->filePath);
        return $path;
    }

    //######################################################################
    // PROTECTED
    //######################################################################

    protected string $filePath = '';
    protected string $rootPath = '';
}
