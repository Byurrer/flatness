<?php

namespace Flatness\Core\Services;

use Flatness\Core\FileSystem\FileInterface;
use Flatness\Core\FileSystem\DirectoryInterface;

/**
 * Интерфейс файловой системы контента
 */
interface ContentInterface
{
    /**
     * Получить объект файла (рекурсивный обход)
     *
     * @param string $uri имя файла без расширения
     * @return FileInterface|null
     */
    public function getFile(string $uri): ?FileInterface;

    /**
     * Получить объект директории
     *
     * @param string $uri путь относительно корня контента, для корня файлов контента нужно передать пустую строку или /
     * @return DirectoryInterface|null
     */
    public function getDirectory(string $uri): ?DirectoryInterface;
}
