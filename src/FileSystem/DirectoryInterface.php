<?php

namespace Flatness\Core\FileSystem;

/**
 * Интерфейс директории
 */
interface DirectoryInterface
{
    /**
     * Получить итератор по файлам
     *
     * @return FileIterator
     */
    public function getFileIterator(): FileIterator;

    /**
     * Получить итератор по директориям
     *
     * @return DirectoryIterator
     */
    public function getDirectoryIterator(): DirectoryIterator;

    /**
     * Получить иднексный файл директории с описанием
     *
     * @return FileInterface
     */
    public function getIndex(): FileInterface;

    /**
     * Получить название деректории
     *
     * @return string
     */
    public function getName(): string;
}
